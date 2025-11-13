<?php

class BookingController
{
    public $booking;
    public $tours;

    public function __construct()
    {
        $this->booking = new Booking();
        $this->tours = new Tours();
    }

    /**
     * Hiển thị danh sách tất cả booking
     */
    public function index()
    {
        $bookings = $this->booking->getAllBookings();
        $stats = $this->booking->getBookingStats();
        require_once PATH_VIEW . 'booking_list.php';
    }

    /**
     * Tìm kiếm booking
     */
    public function search()
    {
        $keyword = $_GET['keyword'] ?? '';
        $status = $_GET['status'] ?? '';
        $date_from = $_GET['date_from'] ?? '';
        $date_to = $_GET['date_to'] ?? '';
        
        $bookings = $this->booking->searchBookings($keyword, $status, $date_from, $date_to);
        $stats = $this->booking->getBookingStats();
        require_once PATH_VIEW . 'booking_list.php';
    }

    /**
     * Hiển thị form thêm booking mới
     */
    public function add()
    {
        $tours = $this->tours->getAlltour();
        $users = $this->booking->getAllUsers();
        require_once PATH_VIEW . 'booking_add.php';
    }

    /**
     * Xử lý thêm booking mới
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_POST['user_id'];
            $tour_id = $_POST['tour_id'];
            $booking_date = $_POST['booking_date'];
            $number_of_people = $_POST['number_of_people'];
            $total_price = $_POST['total_price'];
            $notes = $_POST['notes'] ?? '';
            $status = $_POST['status'] ?? 'pending';

            // Validate dữ liệu
            if (empty($user_id) || empty($tour_id) || empty($booking_date) || empty($number_of_people)) {
                $_SESSION['error_message'] = "Vui lòng điền đầy đủ thông tin bắt buộc!";
                $_SESSION['old_data'] = $_POST;
                header('Location: ?action=booking_add');
                exit();
            }

            // Validate ngày booking
            if (strtotime($booking_date) < strtotime('today')) {
                $_SESSION['error_message'] = "Ngày booking không thể là ngày trong quá khứ!";
                $_SESSION['old_data'] = $_POST;
                header('Location: ?action=booking_add');
                exit();
            }

            if ($this->booking->addBooking($user_id, $tour_id, $booking_date, $number_of_people, $total_price, $notes, $status)) {
                $_SESSION['success_message'] = "Thêm booking thành công!";
                header('Location: ?action=bookings');
            } else {
                $_SESSION['error_message'] = "Có lỗi xảy ra khi thêm booking!";
                header('Location: ?action=booking_add');
            }
            exit();
        }
    }

    /**
     * Hiển thị chi tiết booking
     */
    public function detail()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $booking = $this->booking->getBookingById($id);
            if ($booking) {
                require_once PATH_VIEW . 'booking_detail.php';
            } else {
                $_SESSION['error_message'] = "Booking không tồn tại!";
                header('Location: ?action=bookings');
                exit();
            }
        }
    }

    /**
     * Hiển thị form chỉnh sửa booking
     */
    public function edit()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $booking = $this->booking->getBookingById($id);
            
            if (!$booking) {
                $_SESSION['error_message'] = "Booking không tồn tại!";
                header('Location: ?action=bookings');
                exit();
            }

            $tours = $this->tours->getAlltour();
            $users = $this->booking->getAllUsers();
            $guides = $this->booking->getAllGuides();
            
            require_once PATH_VIEW . 'booking_edit.php';
        }
    }

    /**
     * Xử lý cập nhật booking
     */
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $user_id = $_POST['user_id'];
            $tour_id = $_POST['tour_id'];
            $booking_date = $_POST['booking_date'];
            $number_of_people = $_POST['number_of_people'];
            $total_price = $_POST['total_price'];
            $notes = $_POST['notes'] ?? '';
            $status = $_POST['status'];

            // Validate dữ liệu
            if (empty($user_id) || empty($tour_id) || empty($booking_date) || empty($number_of_people)) {
                $_SESSION['error_message'] = "Vui lòng điền đầy đủ thông tin bắt buộc!";
                header("Location: ?action=booking_edit&id=$id");
                exit();
            }

            if ($this->booking->updateBooking($id, $user_id, $tour_id, $booking_date, $number_of_people, $total_price, $notes, $status)) {
                $_SESSION['success_message'] = "Cập nhật booking thành công!";
                header('Location: ?action=bookings');
            } else {
                $_SESSION['error_message'] = "Có lỗi xảy ra khi cập nhật booking!";
                header("Location: ?action=booking_edit&id=$id");
            }
            exit();
        }
    }

    /**
     * Xóa booking
     */
    public function delete()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            
            if ($this->booking->deleteBooking($id)) {
                $_SESSION['success_message'] = "Xóa booking thành công!";
            } else {
                $_SESSION['error_message'] = "Có lỗi xảy ra khi xóa booking!";
            }
            
            header('Location: ?action=bookings');
            exit();
        }
    }

    /**
     * Hiển thị form gán hướng dẫn viên
     */
    public function assign_guide()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $booking = $this->booking->getBookingById($id);
            
            if (!$booking) {
                $_SESSION['error_message'] = "Booking không tồn tại!";
                header('Location: ?action=bookings');
                exit();
            }

            $guides = $this->booking->getAllGuides();
            require_once PATH_VIEW . 'booking_assign_guide.php';
        }
    }

    /**
     * Xử lý gán hướng dẫn viên
     */
    public function store_assign_guide()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $booking_id = $_POST['booking_id'];
            $guide_id = $_POST['guide_id'];

            if (empty($guide_id)) {
                $_SESSION['error_message'] = "Vui lòng chọn hướng dẫn viên!";
                header("Location: ?action=assign_guide&id=$booking_id");
                exit();
            }

            if ($this->booking->assignGuide($booking_id, $guide_id)) {
                $_SESSION['success_message'] = "Gán hướng dẫn viên thành công!";
                header('Location: ?action=bookings');
            } else {
                $_SESSION['error_message'] = "Có lỗi xảy ra khi gán hướng dẫn viên!";
                header("Location: ?action=assign_guide&id=$booking_id");
            }
            exit();
        }
    }

    /**
     * Cập nhật trạng thái booking
     */
    public function update_status()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $booking_id = $_POST['booking_id'];
            $status = $_POST['status'];

            if ($this->booking->updateStatus($booking_id, $status)) {
                $_SESSION['success_message'] = "Cập nhật trạng thái thành công!";
            } else {
                $_SESSION['error_message'] = "Có lỗi xảy ra khi cập nhật trạng thái!";
            }
            
            header('Location: ?action=bookings');
            exit();
        }
    }

    /**
     * Lấy thông tin tour qua Ajax để tính giá
     */
    public function get_tour_info()
    {
        if (isset($_GET['tour_id'])) {
            $tour_id = $_GET['tour_id'];
            $tour = $this->tours->getTourById($tour_id);
            
            header('Content-Type: application/json');
            echo json_encode($tour);
            exit();
        }
    }

    /**
     * Xuất danh sách booking ra Excel/CSV
     */
    public function export()
    {
        $bookings = $this->booking->getAllBookings();
        
        $filename = "bookings_" . date('Y-m-d') . ".csv";
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // BOM cho UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Tiêu đề cột
        fputcsv($output, [
            'Mã Booking',
            'Khách hàng', 
            'Email',
            'Tour',
            'Ngày đặt',
            'Số người',
            'Tổng tiền',
            'HDV',
            'Trạng thái',
            'Ngày tạo'
        ]);
        
        // Dữ liệu
        foreach ($bookings as $booking) {
            fputcsv($output, [
                $booking['booking_code'],
                $booking['user_name'],
                $booking['user_email'],
                $booking['tour_name'],
                $booking['booking_date'],
                $booking['number_of_people'],
                number_format($booking['total_price']) . ' VND',
                $booking['guide_name'] ?? 'Chưa gán',
                $this->getStatusText($booking['status']),
                date('d/m/Y H:i', strtotime($booking['created_at']))
            ]);
        }
        
        fclose($output);
        exit();
    }

    /**
     * Hàm hỗ trợ - Chuyển đổi status thành text tiếng Việt
     */
    private function getStatusText($status)
    {
        $statusTexts = [
            'pending' => 'Chờ xử lý',
            'confirmed' => 'Đã xác nhận',
            'assigned' => 'Đã gán HDV',
            'in_progress' => 'Đang thực hiện',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy'
        ];
        
        return $statusTexts[$status] ?? $status;
    }
}