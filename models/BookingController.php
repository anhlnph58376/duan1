<?php

// Sao chép controller vào models/ để tương thích autoload
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
        $bookings = $this->booking->getAllBookings();
        $stats = $this->booking->getBookingStats();
        
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

        require_once PATH_VIEW . 'booking_detail.php';
    }

    // Hiển thị form thêm booking
    public function booking_add()
    {
        $customers = $this->booking->getAllCustomers();
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
            $total_amount = trim($_POST['total_amount']);
            $deposit_amount = trim($_POST['deposit_amount'] ?? 0);
            $status = trim($_POST['status'] ?? 'Pending');
            
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
            
            // Tạo data array cho booking
            $data = [
                'customer_name' => $customer_name,
                'customer_email' => $customer_email,
                'customer_phone' => $customer_phone,
                'customer_address' => $customer_address,
                'booking_date' => $booking_date,
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
                    $_SESSION['error'] = 'Thêm booking thất bại!';
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
            
            // Thêm booking
            $result = $this->booking->addBooking(
                $tour_id, $customer_name, $customer_email, $customer_phone,
                $number_of_people, $booking_date, $special_requests, 'pending'
            );
            
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
}
