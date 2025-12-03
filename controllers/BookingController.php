<?php
class BookingController
{
    protected $booking;
    protected $tours;

    public function __construct()
    {
        $this->booking = new Booking();
        $this->tours = new Tours();
    }

    // Hiển thị danh sách booking
    public function bookings()
    {
        $bookings = $this->booking->getAllBookingsWithDepartures();
        $stats = $this->booking->getBookingStats();
        $departures = $this->booking->getAvailableDepartures();
        // Load tours for filters/display
        $tours = $this->tours->getAllTours();
        // Group bookings so each booking has a `departures` array (may be empty)
        $grouped = [];
        if (is_array($bookings)) {
            foreach ($bookings as $row) {
                $id = $row['id'] ?? null;
                if ($id === null) continue;
                if (!isset($grouped[$id])) {
                    $grouped[$id] = $row;
                    $grouped[$id]['departures'] = [];
                }
                if (!empty($row['departure_id'])) {
                    $grouped[$id]['departures'][] = [
                        'departure_id' => $row['departure_id'],
                        'departure_date' => $row['departure_date'] ?? null
                    ];
                }
            }
            $bookings = array_values($grouped);
        }

        require_once PATH_VIEW . 'bookings.php';
    }

    // Hiển thị chi tiết booking
    public function booking_detail()
    {
        $id = $_GET['id'] ?? 0;
        $booking = $this->booking->getBookingById($id);
        
        if (!$booking) {
            $_SESSION['error'] = 'Không tìm thấy booking!';
            header('Location: ?action=bookings');
            exit;
        }

        $members = $this->booking->getMembers($id);

        // Try to determine related tour id for this booking so we can link to tour detail
        $tour_id = null;
        if (!empty($booking['tour_id'])) {
            $tour_id = $booking['tour_id'];
        } else {
            try {
                $pdo = $this->booking->getPdo();
                $sql = "SELECT tv.tour_id FROM departure_bookings db JOIN departures d ON db.departure_id = d.id LEFT JOIN tour_versions tv ON d.tour_version_id = tv.id WHERE db.booking_id = :booking_id LIMIT 1";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':booking_id' => $id]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!empty($row['tour_id'])) $tour_id = $row['tour_id'];
            } catch (Exception $e) {
                // ignore, tour link will be absent
            }
        }

        require_once PATH_VIEW . 'booking_detail.php';
    }

    // Hiển thị form thêm booking
    public function booking_add()
    {
        $customers = $this->booking->getAllCustomers();
        $tours = $this->tours->getAllTours();
        require_once PATH_VIEW . 'booking_add.php';
    }

    // Xử lý thêm booking
    public function addBooking()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Lấy dữ liệu từ form
            $customer_name = trim($_POST['customer_name']);
            $customer_email = trim($_POST['customer_email']); 
            $customer_phone = trim($_POST['customer_phone']);
            $customer_address = trim($_POST['customer_address'] ?? '');
            $booking_date = trim($_POST['booking_date']);
            $pax_count = isset($_POST['pax_count']) ? (int)$_POST['pax_count'] : 1;
            $total_amount = trim($_POST['total_amount']);
            $deposit_amount = trim($_POST['deposit_amount'] ?? 0);
            // booking_type: 'individual' or 'group'
            $booking_type = trim($_POST['booking_type'] ?? 'individual');
            $special_requests = trim($_POST['special_requests'] ?? '');
            // default to a tentative booking while availability is confirmed
            $status = trim($_POST['status'] ?? 'Tentative');
            
            // Validate dữ liệu
            $errors = [];
            
            if (empty($customer_name)) {
                $errors[] = 'Tên khách hàng không được để trống!';
            }
            
            if (empty($customer_phone)) {
                $errors[] = 'Số điện thoại không được để trống!';
            }
            
            if (empty($booking_date)) {
                $errors[] = 'Ngày đặt tour không được để trống!';
            }
            
            if (empty($total_amount) || $total_amount < 0) {
                $errors[] = 'Tổng tiền phải lớn hơn 0!';
            }
            
            // Nếu có lỗi, lưu lại dữ liệu cũ và hiển thị lỗi
            if (!empty($errors)) {
                $_SESSION['error_booking'] = implode('<br>', $errors);
                $_SESSION['old_data'] = $_POST;
                header('Location: ?action=booking_add');
                exit;
            }
            
            // Tạo data array cho booking (bao gồm pax_count, loại booking, yêu cầu đặc biệt)
            $data = [
                'customer_name' => $customer_name,
                'customer_email' => $customer_email,
                'customer_phone' => $customer_phone,
                'customer_address' => $customer_address,
                'booking_date' => $booking_date,
                'tour_id' => $_POST['tour_id'] ?? null,
                'pax_count' => $pax_count,
                'booking_type' => $booking_type,
                'special_requests' => $special_requests,
                'total_amount' => $total_amount,
                'deposit_amount' => $deposit_amount,
                'status' => $status
            ];
            
            try {
                $result = $this->booking->addBooking($data);
                if ($result) {
                    $_SESSION['success'] = 'Thêm booking thành công!';
                    header('Location: ?action=bookings');
                } else {
                    $errorDetail = method_exists($this->booking, 'getLastError') ? $this->booking->getLastError() : '';
                    $_SESSION['error'] = 'Thêm booking thất bại! ' . ($errorDetail ? ('Chi tiết: ' . $errorDetail) : '');
                    header('Location: ?action=booking_add');
                }
            } catch (Exception $e) {
                $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
                header('Location: ?action=booking_add');
            }
            exit;
        }
    }

    // Hiển thị form sửa booking
    public function booking_edit()
    {
        $id = $_GET['id'] ?? 0;
        $booking = $this->booking->getBookingById($id);
        
        if (!$booking) {
            $_SESSION['error'] = 'Không tìm thấy booking!';
            header('Location: ?action=bookings');
            exit;
        }

        $customers = $this->booking->getAllCustomers();
        // Load all tours so the edit form can change the associated tour
        $tours = $this->tours->getAllTours();
        require_once PATH_VIEW . 'booking_edit.php';
    }

    // Xử lý cập nhật booking
    public function updateBooking()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = trim($_POST['id']);
            $customer_name = trim($_POST['customer_name']);
            $customer_email = trim($_POST['customer_email']);
            $customer_phone = trim($_POST['customer_phone']);
            $customer_address = trim($_POST['customer_address'] ?? '');
            $booking_code = trim($_POST['booking_code']);
            $booking_date = trim($_POST['booking_date']);
            $total_amount = trim($_POST['total_amount']);
            $deposit_amount = trim($_POST['deposit_amount'] ?? 0);
            $status = trim($_POST['status']);
            
            // Validate dữ liệu
            $errors = [];
            
            if (empty($customer_name)) {
                $errors[] = 'Tên khách hàng không được để trống!';
            }
            
            if (empty($customer_phone)) {
                $errors[] = 'Số điện thoại không được để trống!';
            }
            
            if (empty($booking_code)) {
                $errors[] = 'Mã booking không được để trống!';
            }
            
            if (empty($booking_date)) {
                $errors[] = 'Ngày đặt tour không được để trống!';
            }
            
            if (empty($total_amount) || $total_amount < 0) {
                $errors[] = 'Tổng tiền phải lớn hơn 0!';
            }
            
            if (empty($status)) {
                $errors[] = 'Vui lòng chọn trạng thái!';
            }
            
            if (!empty($errors)) {
                $_SESSION['error_booking'] = implode('<br>', $errors);
                $_SESSION['old_data'] = $_POST;
                header('Location: ?action=booking_edit&id=' . $id);
                exit;
            }
            
            // Tạo data array cho cập nhật
            $data = [
                'customer_name' => $customer_name,
                'customer_email' => $customer_email,
                'customer_phone' => $customer_phone,
                'customer_address' => $customer_address,
                'booking_code' => $booking_code,
                'booking_date' => $booking_date,
                'tour_id' => $_POST['tour_id'] ?? null,
                'total_amount' => $total_amount,
                'deposit_amount' => $deposit_amount,
                'status' => $status
            ];
            
            // Cập nhật booking
            $result = $this->booking->updateBooking($id, $data);
            
            if ($result) {
                $_SESSION['success'] = 'Cập nhật booking thành công!';
                header('Location: ?action=bookings');
            } else {
                $_SESSION['error'] = 'Cập nhật booking thất bại!';
                header('Location: ?action=booking_edit&id=' . $id);
            }
            exit;
        }
    }

    // Xử lý xóa booking
    public function booking_delete()
    {
        $id = $_GET['id'] ?? 0;
        
        if ($id) {
            $result = $this->booking->deleteBooking($id);
            
            if ($result) {
                $_SESSION['success'] = 'Xóa booking thành công!';
            } else {
                $_SESSION['error'] = 'Xóa booking thất bại!';
            }
        } else {
            $_SESSION['error'] = 'ID booking không hợp lệ!';
        }
        
        header('Location: ?action=bookings');
        exit;
    }

    // Cập nhật trạng thái booking
    public function updateBookingStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'] ?? 0;
            $status = $_POST['status'] ?? '';
            
            if ($id && $status) {
                $result = $this->booking->updateBookingStatus($id, $status);
                
                if ($result) {
                    $_SESSION['success'] = 'Cập nhật trạng thái thành công!';
                } else {
                    $_SESSION['error'] = 'Cập nhật trạng thái thất bại!';
                }
            } else {
                $_SESSION['error'] = 'Dữ liệu không hợp lệ!';
            }
        }
        
        header('Location: ?action=bookings');
        exit;
    }

    // Xử lý booking từ trang tour detail
    public function bookTour()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $tour_id = trim($_POST['tour_id']);
            $customer_name = trim($_POST['customer_name']);
            $customer_email = trim($_POST['customer_email']);
            $customer_phone = trim($_POST['customer_phone']);
            $number_of_people = trim($_POST['number_of_people']);
            $booking_date = trim($_POST['booking_date']);
            $special_requests = trim($_POST['special_requests']);
            
            // Validate dữ liệu
            $errors = [];
            
            if (empty($customer_name)) {
                $errors[] = 'Tên khách hàng không được để trống!';
            }
            
            if (empty($customer_email)) {
                $errors[] = 'Email không được để trống!';
            } elseif (!filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Email không hợp lệ!';
            }
            
            if (empty($customer_phone)) {
                $errors[] = 'Số điện thoại không được để trống!';
            }
            
            if (empty($number_of_people) || $number_of_people < 1) {
                $errors[] = 'Số lượng người phải lớn hơn 0!';
            }
            
            if (empty($booking_date)) {
                $errors[] = 'Ngày đặt tour không được để trống!';
            } elseif (strtotime($booking_date) < strtotime('today')) {
                $errors[] = 'Ngày đặt tour không được là ngày trong quá khứ!';
            }
            
            if (!empty($errors)) {
                $_SESSION['error_booking'] = implode('<br>', $errors);
                $_SESSION['old_data'] = $_POST;
                header('Location: ?action=tour_detail&id=' . $tour_id);
                exit;
            }
            
            // Thêm booking (gọi với mảng dữ liệu theo signature hiện tại)
            $data = [
                'tour_id' => $tour_id,
                'customer_name' => $customer_name,
                'customer_email' => $customer_email,
                'customer_phone' => $customer_phone,
                'pax_count' => (int)$number_of_people,
                'booking_date' => $booking_date,
                'special_requests' => $special_requests,
                'total_amount' => 0,
                'deposit_amount' => 0,
                'status' => 'Pending'
            ];

            $result = $this->booking->addBooking($data);
            
            if ($result) {
                $_SESSION['success'] = 'Đặt tour thành công! Chúng tôi sẽ liên hệ với bạn sớm nhất.';
                header('Location: ?action=tour_detail&id=' . $tour_id);
            } else {
                $_SESSION['error'] = 'Đặt tour thất bại! Vui lòng thử lại.';
                header('Location: ?action=tour_detail&id=' . $tour_id);
            }
            exit;
        }
    }

    /**
     * Thêm booking vào departure
     */
    // NOTE: việc thêm booking vào đoàn quản lý bởi DepartureController
    // (chức năng di chuyển đã di dời sang phần quản lý đoàn).

    // --- Quản lý thành viên booking ---
    // Hiển thị danh sách thành viên của một booking
    public function booking_members()
    {
        $booking_id = $_GET['booking_id'] ?? 0;
        $booking = $this->booking->getBookingById($booking_id);
        if (!$booking) {
            $_SESSION['error'] = 'Không tìm thấy booking!';
            header('Location: ?action=bookings');
            exit;
        }

        $members = $this->booking->getMembers($booking_id);
        require_once PATH_VIEW . 'booking_members.php';
    }

    // Partial view for AJAX: render only the members HTML for a booking
    public function booking_members_partial()
    {
        $booking_id = $_GET['booking_id'] ?? 0;
        $booking = $this->booking->getBookingById($booking_id);
        if (!$booking) {
            http_response_code(404);
            echo 'Booking không tồn tại';
            return;
        }
        $members = $this->booking->getMembers($booking_id);
        require_once PATH_VIEW . 'booking_members_partial.php';
    }

    // Form thêm thành viên
    public function booking_member_add()
    {
        $booking_id = $_GET['booking_id'] ?? 0;
        $booking = $this->booking->getBookingById($booking_id);
        if (!$booking) {
            $_SESSION['error'] = 'Không tìm thấy booking!';
            header('Location: ?action=bookings');
            exit;
        }

        $member = null;
        // allow optional departure context so form can add a member specifically to a departure
        $departure_id = $_GET['departure_id'] ?? null;
        require_once PATH_VIEW . 'booking_member_form.php';
    }

    // Xử lý lưu thành viên mới
    public function booking_member_store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?action=bookings');
            exit;
        }

        $booking_id = $_POST['booking_id'] ?? 0;
        $data = [
            'full_name' => trim($_POST['full_name'] ?? ''),
            'age' => trim($_POST['age'] ?? ''),
            'gender' => trim($_POST['gender'] ?? ''),
            'passport_number' => trim($_POST['passport_number'] ?? ''),
            'note' => trim($_POST['note'] ?? ''),
            'payment_status' => trim($_POST['payment_status'] ?? ''),
            'payment_amount' => isset($_POST['payment_amount']) ? (float)$_POST['payment_amount'] : 0,
            'checkin_status' => trim($_POST['checkin_status'] ?? ''),
            'room_assignment' => trim($_POST['room_assignment'] ?? ''),
            'special_request' => trim($_POST['special_request'] ?? '')
        ];
        // allow optional target departure when adding member from a departure context
        $departure_id = !empty($_POST['departure_id']) ? (int)$_POST['departure_id'] : null;
        if ($departure_id) $data['departure_id'] = $departure_id;

        if (!$booking_id || empty($data['full_name'])) {
            $_SESSION['error'] = 'Dữ liệu không hợp lệ!';
            header('Location: ?action=booking_member_add&booking_id=' . $booking_id);
            exit;
        }

        $res = $this->booking->addMember($booking_id, $data);
        if ($res) {
            $_SESSION['success'] = 'Thêm thành viên thành công!';
        } else {
            $reason = $this->booking->getLastError();
            // Luôn ghi log chi tiết trong model; ở đây ta hiển thị thông báo thân thiện
            if (defined('APP_DEBUG') && APP_DEBUG) {
                // Môi trường phát triển: hiển thị chi tiết
                $_SESSION['error'] = 'Thêm thành viên thất bại: ' . ($reason ? $reason : 'Lý do không rõ');
            } else {
                // Môi trường production: không lộ chi tiết, chỉ hiển thị thông báo chung
                $_SESSION['error'] = 'Thêm thành viên thất bại: Lỗi hệ thống. Vui lòng liên hệ quản trị.';
            }
        }

        // After adding member, decide where to redirect
        if (!empty($departure_id)) {
            header('Location: ?action=departure_detail&id=' . $departure_id);
        } else {
            header('Location: ?action=booking_members&booking_id=' . $booking_id);
        }
        exit;
    }

    // Form sửa thành viên
    public function booking_member_edit()
    {
        $id = $_GET['id'] ?? 0;
        $member = $this->booking->getMemberById($id);
        if (!$member) {
            $_SESSION['error'] = 'Không tìm thấy thành viên!';
            header('Location: ?action=bookings');
            exit;
        }

        $booking = $this->booking->getBookingById($member['booking_id']);
        require_once PATH_VIEW . 'booking_member_form.php';
    }

    // Xử lý cập nhật thành viên
    public function booking_member_update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?action=bookings');
            exit;
        }

        $id = $_POST['id'] ?? 0;
        $booking_id = $_POST['booking_id'] ?? 0;
        $data = [
            'full_name' => trim($_POST['full_name'] ?? ''),
            'age' => trim($_POST['age'] ?? ''),
            'gender' => trim($_POST['gender'] ?? ''),
            'passport_number' => trim($_POST['passport_number'] ?? ''),
            'note' => trim($_POST['note'] ?? ''),
            'payment_status' => trim($_POST['payment_status'] ?? ''),
            'payment_amount' => isset($_POST['payment_amount']) ? $_POST['payment_amount'] : null,
            'checkin_status' => trim($_POST['checkin_status'] ?? ''),
            'room_assignment' => trim($_POST['room_assignment'] ?? ''),
            'special_request' => trim($_POST['special_request'] ?? '')
        ];

        if (!$id || !$booking_id || empty($data['full_name'])) {
            $_SESSION['error'] = 'Dữ liệu không hợp lệ!';
            header('Location: ?action=booking_edit&id=' . $booking_id);
            exit;
        }

        $res = $this->booking->updateMember($id, $data);
        if ($res) {
            $_SESSION['success'] = 'Cập nhật thành viên thành công!';
        } else {
            $_SESSION['error'] = 'Cập nhật thành viên thất bại!';
        }

        header('Location: ?action=booking_members&booking_id=' . $booking_id);
        exit;
    }

    // Xử lý xóa thành viên
    public function booking_member_delete()
    {
        $id = $_GET['id'] ?? 0;
        $member = $this->booking->getMemberById($id);
        if (!$member) {
            $_SESSION['error'] = 'Thành viên không tồn tại!';
            header('Location: ?action=bookings');
            exit;
        }

        $res = $this->booking->deleteMember($id);
        if ($res) {
            $_SESSION['success'] = 'Xóa thành viên thành công!';
        } else {
            $_SESSION['error'] = 'Xóa thành viên thất bại!';
        }

        header('Location: ?action=booking_members&booking_id=' . $member['booking_id']);
        exit;
    }

    // Partial/detail view for a single member (used by AJAX/modal)
    public function booking_member_detail()
    {
        $id = $_GET['id'] ?? 0;
        $member = $this->booking->getMemberById($id);
        if (!$member) {
            http_response_code(404);
            echo 'Thành viên không tồn tại';
            return;
        }

        // render a partial that shows member details
        require_once PATH_VIEW . 'booking_member_detail_partial.php';
    }

    // Assign guide to booking
    public function booking_assign_guide()
    {
        $booking_id = $_GET['id'] ?? 0;
        $booking = $this->booking->getBookingById($booking_id);

        if (!$booking) {
            $_SESSION['error'] = 'Không tìm thấy booking!';
            header('Location: ?action=bookings');
            exit;
        }

        // Get booking with guide info
        $bookingWithGuide = $this->booking->getAllBookingsWithDepartures();
        $bookingData = null;
        foreach ($bookingWithGuide as $b) {
            if ($b['id'] == $booking_id) {
                $bookingData = $b;
                break;
            }
        }
        if (!$bookingData) {
            $bookingData = $booking;
        }

        // Get available guides
        $guideModel = new Guide();
        $guides = $guideModel->getAvailableGuides(date('Y-m-d'));

        // Handle search
        $search_term = $_GET['search'] ?? '';
        if (!empty($search_term)) {
            // Search among available guides only
            $all_guides = $guideModel->getAvailableGuides(date('Y-m-d'));
            $searched_guides = $guideModel->searchGuides($search_term);
            // Filter to only show guides that are both available and match search
            $guides = array_filter($all_guides, function($guide) use ($searched_guides) {
                foreach ($searched_guides as $searched_guide) {
                    if ($guide['id'] == $searched_guide['id']) {
                        return true;
                    }
                }
                return false;
            });
        }

        $booking = $bookingData; // override

        require_once PATH_VIEW . 'booking_assign_guide.php';
    }

    /**
     * Ensure that a guide's user account has the tour_guide_id properly set
     */
    private function ensureGuideUserLink($guide_id)
    {
        try {
            $guideModel = new Guide();
            $guide = $guideModel->getGuideById($guide_id);

            if ($guide && !empty($guide['user_id'])) {
                $userModel = new User();
                $user = $userModel->find($guide['user_id']);

                // Check if tour_guide_id needs to be updated
                if (empty($user['tour_guide_id']) || $user['tour_guide_id'] != $guide_id) {
                    $userModel->update($guide['user_id'], [
                        'tour_guide_id' => $guide_id
                    ]);
                    error_log("Updated user {$guide['user_id']} with tour_guide_id {$guide_id}");
                }
            }
        } catch (Exception $e) {
            error_log("Error ensuring guide user link: " . $e->getMessage());
        }
    }

    // Store guide assignment
    public function store_assign_guide()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?action=bookings');
            exit;
        }

        $booking_id = $_POST['booking_id'] ?? 0;
        $guide_id = $_POST['guide_id'] ?? null;
        $message = $_POST['message'] ?? '';

        if (!$booking_id) {
            $_SESSION['error_message'] = 'ID booking không hợp lệ!';
            header('Location: ?action=bookings');
            exit;
        }

        $booking = $this->booking->getBookingById($booking_id);
        if (!$booking) {
            $_SESSION['error_message'] = 'Không tìm thấy booking!';
            header('Location: ?action=bookings');
            exit;
        }

        if ($guide_id) {
            // Assign guide
            $result = $this->booking->assignGuide($booking_id, $guide_id);
            if ($result) {
                $_SESSION['success'] = 'Gán hướng dẫn viên thành công!';
                // If there's a message, store it as a note or send notification
                if (!empty($message)) {
                    // Store message as booking note or send to guide
                    $this->booking->updateBookingNote($booking_id, $message);
                }

                // Ensure the guide's user account has the tour_guide_id set
                $this->ensureGuideUserLink($guide_id);
            } else {
                $_SESSION['error_message'] = 'Gán hướng dẫn viên thất bại!';
            }
        } else {
            // Remove guide assignment
            $result = $this->booking->assignGuide($booking_id, null);
            if ($result) {
                $_SESSION['success'] = 'Hủy gán hướng dẫn viên thành công!';
            } else {
                $_SESSION['error_message'] = 'Hủy gán hướng dẫn viên thất bại!';
            }
        }

        header('Location: ?action=booking_detail&id=' . $booking_id);
        exit;
    }
}
