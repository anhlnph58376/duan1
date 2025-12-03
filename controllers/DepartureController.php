<?php

class DepartureController
{
    private $departureModel;

    public function __construct()
    {
        $this->departureModel = new Departure();
    }

    /**
     * Hiển thị danh sách đoàn
     */
    public function index()
    {
        try {
            $bookingModel = new Booking();
            $bookings_with_members = $bookingModel->getBookingsWithMembers();
            require_once PATH_VIEW . 'departures.php';
        } catch (Exception $e) {
            $_SESSION['message'] = 'Có lỗi khi tải danh sách đoàn!';
            $_SESSION['message_type'] = 'error';
            error_log("Departure controller error: " . $e->getMessage());
            require_once PATH_VIEW . 'departures.php';
        }
    }

    /**
     * Hiển thị form thêm đoàn
     */
    public function add()
    {
        // Chức năng tạo đoàn đã bị vô hiệu hoá. Chuyển hướng về danh sách đoàn.
        $_SESSION['message'] = 'Chức năng tạo đoàn mới đã bị tắt.';
        $_SESSION['message_type'] = 'info';
        header('Location: index.php?action=departures');
        exit;
    }

    /**
     * Xử lý tạo đoàn mới
     */
    public function create()
    {
        // Tạo đoàn đã bị tắt trong phiên bản này.
        $_SESSION['message'] = 'Tạo đoàn bị vô hiệu hóa. Thao tác bị huỷ.';
        $_SESSION['message_type'] = 'info';
        header('Location: index.php?action=departures');
        exit;
    }

    /**
     * Hiển thị chi tiết đoàn
     */
    public function detail()
    {
        $id = $_GET['id'] ?? 0;
        
        if (!$id) {
            $_SESSION['message'] = 'ID đoàn không hợp lệ!';
            $_SESSION['message_type'] = 'error';
            header('Location: index.php?action=departures');
            exit;
        }

        try {
            $departure = $this->departureModel->getDepartureById($id);
            
            if (!$departure) {
                $_SESSION['message'] = 'Không tìm thấy đoàn!';
                $_SESSION['message_type'] = 'error';
                header('Location: index.php?action=departures');
                exit;
            }

            // Lấy danh sách booking của đoàn này
            $bookings = $this->departureModel->getDepartureBookings($id);
            
            require_once PATH_VIEW . 'departure_detail.php';
        } catch (Exception $e) {
            $_SESSION['message'] = 'Có lỗi khi tải chi tiết đoàn!';
            $_SESSION['message_type'] = 'error';
            header('Location: index.php?action=departures');
            exit;
        }
    }

    /**
     * Hiển thị form chỉnh sửa đoàn
     */
    public function edit()
    {
        $id = $_GET['id'] ?? 0;
        
        if (!$id) {
            $_SESSION['message'] = 'ID đoàn không hợp lệ!';
            $_SESSION['message_type'] = 'error';
            header('Location: index.php?action=departures');
            exit;
        }

        try {
            $departure = $this->departureModel->getDepartureById($id);
            
            if (!$departure) {
                $_SESSION['message'] = 'Không tìm thấy đoàn!';
                $_SESSION['message_type'] = 'error';
                header('Location: index.php?action=departures');
                exit;
            }

            $tours = $this->departureModel->getAllTours();
            
            require_once PATH_VIEW . 'departure_edit.php';
        } catch (Exception $e) {
            $_SESSION['message'] = 'Có lỗi khi tải form chỉnh sửa!';
            $_SESSION['message_type'] = 'error';
            header('Location: index.php?action=departures');
            exit;
        }
    }

    /**
     * Xử lý cập nhật đoàn
     */
    public function update()
    {
        $id = $_GET['id'] ?? 0;
        
        if (!$id || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['message'] = 'Yêu cầu không hợp lệ!';
            $_SESSION['message_type'] = 'error';
            header('Location: index.php?action=departures');
            exit;
        }

        try {
            $data = [
                'departure_date' => $_POST['departure_date'] . ' 08:00:00',
                'return_date' => $_POST['return_date'] . ' 18:00:00',
                'status' => $_POST['status'] ?? 'Scheduled',
                'min_pax' => $_POST['min_pax'] ?? 1,
                'max_pax' => $_POST['max_pax'] ?? 50,
                'actual_guide_id' => !empty($_POST['actual_guide_id']) ? $_POST['actual_guide_id'] : null
            ];

            $result = $this->departureModel->updateDeparture($id, $data);
            
            if ($result) {
                $_SESSION['message'] = 'Cập nhật đoàn thành công!';
                $_SESSION['message_type'] = 'success';
                header('Location: index.php?action=departure_detail&id=' . $id);
            } else {
                $_SESSION['message'] = 'Có lỗi khi cập nhật đoàn!';
                $_SESSION['message_type'] = 'error';
                header('Location: index.php?action=departure_edit&id=' . $id);
            }
        } catch (Exception $e) {
            $_SESSION['message'] = 'Có lỗi khi cập nhật đoàn: ' . $e->getMessage();
            $_SESSION['message_type'] = 'error';
            header('Location: index.php?action=departure_edit&id=' . $id);
        }
        exit;
    }

    /**
     * Xử lý xóa đoàn
     */
    public function delete()
    {
        $id = $_GET['id'] ?? 0;
        
        if (!$id) {
            $_SESSION['message'] = 'ID đoàn không hợp lệ!';
            $_SESSION['message_type'] = 'error';
            header('Location: index.php?action=departures');
            exit;
        }

        try {
            $result = $this->departureModel->deleteDeparture($id);
            
            if ($result) {
                $_SESSION['message'] = 'Xóa đoàn thành công!';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Không thể xóa đoàn vì có booking liên quan!';
                $_SESSION['message_type'] = 'error';
            }
        } catch (Exception $e) {
            $_SESSION['message'] = 'Có lỗi khi xóa đoàn: ' . $e->getMessage();
            $_SESSION['message_type'] = 'error';
        }
        
        header('Location: index.php?action=departures');
        exit;
    }

    /**
     * Hiển thị form thêm booking cho departure
     */
    public function addBooking()
    {
        $departure_id = $_GET['departure_id'] ?? '';
        
        if (!$departure_id) {
            $_SESSION['message'] = 'ID đoàn không hợp lệ!';
            $_SESSION['message_type'] = 'error';
            header('Location: index.php?action=departures');
            exit;
        }

        try {
            $departure = $this->departureModel->getDepartureById($departure_id);
            $customers = $this->departureModel->getAllCustomers();
            
            if (!$departure) {
                $_SESSION['message'] = 'Không tìm thấy đoàn!';
                $_SESSION['message_type'] = 'error';
                header('Location: index.php?action=departures');
                exit;
            }
            
            require_once PATH_VIEW . 'departure_booking_add.php';
        } catch (Exception $e) {
            $_SESSION['message'] = 'Có lỗi khi tải form thêm booking: ' . $e->getMessage();
            $_SESSION['message_type'] = 'error';
            header('Location: index.php?action=departure_detail&id=' . $departure_id);
            exit;
        }
    }

    /**
     * Xử lý thêm booking cho departure
     */
    public function createBooking()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=departures');
            exit;
        }

        $departure_id = $_POST['departure_id'] ?? '';

        if (!$departure_id) {
            $_SESSION['message'] = 'ID đoàn không hợp lệ!';
            $_SESSION['message_type'] = 'error';
            header('Location: index.php?action=departures');
            exit;
        }

        try {
            $booking_data = [
                'customer_id' => $_POST['customer_id'],
                'total_amount' => $_POST['total_amount'],
                'status' => $_POST['status'] ?? 'Pending',
                'pax_count' => $_POST['pax_count'] ?? 1
            ];

            $result = $this->departureModel->addBookingToDeparture($departure_id, $booking_data);

            if ($result) {
                $_SESSION['message'] = 'Thêm booking thành công!';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Có lỗi khi thêm booking!';
                $_SESSION['message_type'] = 'error';
            }

        } catch (Exception $e) {
            $_SESSION['message'] = 'Có lỗi: ' . $e->getMessage();
            $_SESSION['message_type'] = 'error';
        }

        header('Location: index.php?action=departure_detail&id=' . $departure_id);
        exit;
    }

    /**
     * Xóa booking khỏi departure
     */
    public function removeBooking()
    {
        $departure_id = $_GET['departure_id'] ?? '';
        $booking_id = $_GET['booking_id'] ?? '';

        if (!$departure_id || !$booking_id) {
            $_SESSION['message'] = 'Thông tin không hợp lệ!';
            $_SESSION['message_type'] = 'error';
            header('Location: index.php?action=departures');
            exit;
        }

        try {
            $result = $this->departureModel->removeBookingFromDeparture($departure_id, $booking_id);

            if ($result) {
                $_SESSION['message'] = 'Xóa booking khỏi đoàn thành công!';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Có lỗi khi xóa booking!';
                $_SESSION['message_type'] = 'error';
            }

        } catch (Exception $e) {
            $_SESSION['message'] = 'Có lỗi: ' . $e->getMessage();
            $_SESSION['message_type'] = 'error';
        }

        header('Location: index.php?action=departure_detail&id=' . $departure_id);
        exit;
    }

    /**
     * Thêm booking có sẵn vào departure
     */
    public function addExistingBookingToDeparture()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=departures');
            exit;
        }

        $departure_id = $_POST['departure_id'] ?? '';
        $booking_id = $_POST['booking_id'] ?? '';
        $pax_count = $_POST['pax_count'] ?? 1;

        if (!$departure_id || !$booking_id) {
            $_SESSION['message'] = 'Thông tin không hợp lệ!';
            $_SESSION['message_type'] = 'error';
            header('Location: index.php?action=departures');
            exit;
        }

        try {
            // Sử dụng chung phương thức với chức năng thêm booking từ departure
            $result = $this->departureModel->addExistingBookingToDeparture($departure_id, $booking_id, $pax_count);

            if ($result) {
                $_SESSION['message'] = 'Thêm booking vào đoàn thành công!';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Có lỗi khi thêm booking vào đoàn!';
                $_SESSION['message_type'] = 'error';
            }

        } catch (Exception $e) {
            $_SESSION['message'] = 'Có lỗi: ' . $e->getMessage();
            $_SESSION['message_type'] = 'error';
        }

        header('Location: index.php?action=departures');
        exit;
    }

    public function createNewBooking() {
        $departureId = $_GET['departure_id'] ?? null;
        
        if (!$departureId) {
            $_SESSION['message'] = 'Không tìm thấy thông tin đoàn!';
            $_SESSION['message_type'] = 'error';
            header("Location: index.php?action=departures");
            exit();
        }

        $departure = $this->departureModel->getDepartureById($departureId);
        
        if (!$departure) {
            $_SESSION['message'] = 'Không tìm thấy đoàn!';
            $_SESSION['message_type'] = 'error';
            header("Location: index.php?action=departures");
            exit();
        }

        include 'views/departure_create_new_booking.php';
    }

    public function processNewBooking() {
        if ($_POST) {
            try {
                $departure_id = $_POST['departure_id'];
                $customer_name = $_POST['customer_name'];
                $customer_email = $_POST['customer_email'];
                $customer_phone = $_POST['customer_phone'];
                $customer_address = $_POST['customer_address'];
                $pax_count = (int)$_POST['pax_count'];
                $price_per_person = (float)$_POST['price_per_person'];
                $total_amount = $pax_count * $price_per_person;

                // Tạo customer mới
                $db = new PDO("mysql:host=localhost;dbname=duan1", "root", "");
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                // Insert customer
                $customerSql = "INSERT INTO customers (name, email, phone, address) VALUES (?, ?, ?, ?)";
                $customerStmt = $db->prepare($customerSql);
                $customerStmt->execute([$customer_name, $customer_email, $customer_phone, $customer_address]);
                $customer_id = $db->lastInsertId();

                // Insert booking
                $bookingSql = "INSERT INTO bookings (customer_id, tour_id, booking_date, total_amount, status, pax_count) 
                              VALUES (?, (SELECT tour_id FROM departures WHERE id = ?), NOW(), ?, 'confirmed', ?)";
                $bookingStmt = $db->prepare($bookingSql);
                $bookingStmt->execute([$customer_id, $departure_id, $total_amount, $pax_count]);
                $booking_id = $db->lastInsertId();

                // Link booking to departure
                $this->departureModel->addExistingBookingToDeparture($departure_id, $booking_id, $pax_count);

                $_SESSION['message'] = 'Đã tạo booking mới và thêm vào đoàn thành công!';
                $_SESSION['message_type'] = 'success';
                
                header("Location: index.php?action=departure_detail&id=$departure_id");
                exit();

            } catch (Exception $e) {
                $_SESSION['message'] = 'Có lỗi xảy ra: ' . $e->getMessage();
                $_SESSION['message_type'] = 'error';
                header("Location: index.php?action=departure_create_new_booking&departure_id=$departure_id");
                exit();
            }
        }
    }
}
?>