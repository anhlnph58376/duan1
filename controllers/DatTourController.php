<?php
class DatTourController
{
    protected $datTour;
    protected $tours;

    public function __construct()
    {
        $this->datTour = new DatTour();
        $this->tours = new Tours();
    }

    // Hiển thị danh sách đặt tour
    public function dat_tour()
    {
        $dat_tour = $this->datTour->getAllBookingsWithDepartures();
        $stats = $this->datTour->getBookingStats();
        $departures = $this->datTour->getAvailableDepartures();
        
        require_once PATH_VIEW . 'dat_tour.php';
    }

    // Hiển thị chi tiết đặt tour
    public function dat_tour_chi_tiet()
    {
        $id = $_GET['id'] ?? 0;
        $dat_tour = $this->datTour->getBookingById($id);
        
        if (!$dat_tour) {
            $_SESSION['error'] = 'Không tìm thấy đặt tour!';
            header('Location: ?action=dat_tour');
            exit;
        }

        require_once PATH_VIEW . 'dat_tour_chi_tiet.php';
    }

    // Hiển thị form thêm đặt tour
    public function dat_tour_them()
    {
        $customers = $this->datTour->getAllCustomers();
        require_once PATH_VIEW . 'dat_tour_them.php';
    }

    // Xử lý thêm đặt tour
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
            
            // Kiểm tra dữ liệu
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
                $_SESSION['error_dat_tour'] = implode('<br>', $errors);
                $_SESSION['old_data'] = $_POST;
                header('Location: ?action=dat_tour_them');
                exit;
            }
            
            // Tạo mảng dữ liệu cho đặt tour
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
                $result = $this->datTour->addBooking($data);
                
                if ($result) {
                    $_SESSION['success'] = 'Thêm đặt tour thành công!';
                    header('Location: ?action=dat_tour');
                } else {
                    $_SESSION['error'] = 'Thêm đặt tour thất bại!';
                    header('Location: ?action=dat_tour_them');
                }
            } catch (Exception $e) {
                $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
                header('Location: ?action=dat_tour_them');
            }
            exit;
        }
    }

    // Hiển thị form sửa đặt tour
    public function dat_tour_sua()
    {
        $id = $_GET['id'] ?? 0;
        $dat_tour = $this->datTour->getBookingById($id);
        
        if (!$dat_tour) {
            $_SESSION['error'] = 'Không tìm thấy đặt tour!';
            header('Location: ?action=dat_tour');
            exit;
        }

        $customers = $this->datTour->getAllCustomers();
        require_once PATH_VIEW . 'dat_tour_sua.php';
    }

    // Xử lý cập nhật đặt tour
    public function capNhatDatTour()
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
            
            // Kiểm tra dữ liệu
            $errors = [];
            
            if (empty($customer_name)) {
                $errors[] = 'Tên khách hàng không được để trống!';
            }
            
            if (empty($customer_phone)) {
                $errors[] = 'Số điện thoại không được để trống!';
            }
            
            if (empty($booking_code)) {
                $errors[] = 'Mã đặt tour không được để trống!';
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
                $_SESSION['error_dat_tour'] = implode('<br>', $errors);
                $_SESSION['old_data'] = $_POST;
                header('Location: ?action=dat_tour_sua&id=' . $id);
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
            
            // Cập nhật đặt tour
            $result = $this->datTour->updateBooking($id, $data);
            
            if ($result) {
                $_SESSION['success'] = 'Cập nhật đặt tour thành công!';
                header('Location: ?action=dat_tour');
            } else {
                $_SESSION['error'] = 'Cập nhật đặt tour thất bại!';
                header('Location: ?action=dat_tour_sua&id=' . $id);
            }
            exit;
        }
    }

    // Xử lý xóa đặt tour
    public function dat_tour_xoa()
    {
        $id = $_GET['id'] ?? 0;
        
        if ($id) {
            $result = $this->datTour->deleteBooking($id);
            
            if ($result) {
                $_SESSION['success'] = 'Xóa đặt tour thành công!';
            } else {
                $_SESSION['error'] = 'Xóa đặt tour thất bại!';
            }
        } else {
            $_SESSION['error'] = 'ID đặt tour không hợp lệ!';
        }
        
        header('Location: ?action=dat_tour');
        exit;
    }

    // Cập nhật trạng thái đặt tour
    public function capNhatTrangThaiDatTour()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'] ?? 0;
            $status = $_POST['status'] ?? '';
            
            if ($id && $status) {
                $result = $this->datTour->updateBookingStatus($id, $status);
                
                if ($result) {
                    $_SESSION['success'] = 'Cập nhật trạng thái thành công!';
                } else {
                    $_SESSION['error'] = 'Cập nhật trạng thái thất bại!';
                }
            } else {
                $_SESSION['error'] = 'Dữ liệu không hợp lệ!';
            }
        }
        
        header('Location: ?action=dat_tour');
        exit;
    }

    // Xử lý đặt tour từ trang chi tiết tour
    public function datTourTuChiTiet()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $tour_id = trim($_POST['tour_id']);
            $customer_name = trim($_POST['customer_name']);
            $customer_email = trim($_POST['customer_email']);
            $customer_phone = trim($_POST['customer_phone']);
            $number_of_people = trim($_POST['number_of_people']);
            $booking_date = trim($_POST['booking_date']);
            $special_requests = trim($_POST['special_requests']);
            
            // Kiểm tra dữ liệu
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
                $_SESSION['error_dat_tour'] = implode('<br>', $errors);
                $_SESSION['old_data'] = $_POST;
                header('Location: ?action=tour_detail&id=' . $tour_id);
                exit;
            }
            
            // Thêm đặt tour
            $result = $this->datTour->addBooking(
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

    /**
     * Thêm booking vào departure
     */
    public function addBookingToDeparture()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?action=bookings');
            exit;
        }

        $booking_id = $_POST['booking_id'] ?? '';
        $departure_id = $_POST['departure_id'] ?? '';
        $pax_count = $_POST['pax_count'] ?? 1;

        if (!$booking_id || !$departure_id) {
            $_SESSION['error'] = 'Thông tin không hợp lệ!';
            header('Location: ?action=bookings');
            exit;
        }

        try {
            $result = $this->booking->addBookingToDeparture($booking_id, $departure_id, $pax_count);

            if ($result) {
                $_SESSION['success'] = 'Thêm booking vào đoàn thành công!';
            } else {
                $_SESSION['error'] = 'Có lỗi khi thêm booking vào đoàn!';
            }

        } catch (Exception $e) {
            $_SESSION['error'] = 'Có lỗi: ' . $e->getMessage();
        }

        header('Location: ?action=bookings');
        exit;
    }

    /**
     * Hiển thị form tạo đoàn mới từ đặt tour
     */
    public function taoDoanMoi()
    {
        $datTourId = $_GET['booking_id'] ?? null;
        
        if (!$datTourId) {
            $_SESSION['message'] = 'Không tìm thấy thông tin đặt tour!';
            $_SESSION['message_type'] = 'error';
            header("Location: index.php?action=dat_tour");
            exit();
        }

        $datTour = $this->datTour->getBookingById($datTourId);
        
        if (!$datTour) {
            $_SESSION['message'] = 'Không tìm thấy đặt tour!';
            $_SESSION['message_type'] = 'error';
            header("Location: index.php?action=dat_tour");
            exit();
        }

        $tours = $this->tours->getAllTours();
        include 'views/dat_tour_tao_doan_moi.php';
    }

    /**
     * Xử lý tạo đoàn mới từ đặt tour
     */
    public function xuLyTaoDoanMoi()
    {
        if ($_POST) {
            try {
                $booking_id = $_POST['booking_id'];
                $tour_id = $_POST['tour_id'];
                $departure_date = $_POST['departure_date'];
                $return_date = $_POST['return_date'];
                $min_pax = (int)$_POST['min_pax'];
                $max_pax = (int)$_POST['max_pax'];
                $pax_count = (int)$_POST['pax_count'];
                $status = $_POST['status'] ?? 'Scheduled';

                $db = new PDO("mysql:host=localhost;dbname=duan1", "root", "");
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                // Thêm đoàn mới
                $departureSql = "INSERT INTO departures (tour_id, departure_date, return_date, status, min_pax, max_pax) 
                               VALUES (?, ?, ?, ?, ?, ?)";
                $departureStmt = $db->prepare($departureSql);
                $departureStmt->execute([$tour_id, $departure_date, $return_date, $status, $min_pax, $max_pax]);
                $departure_id = $db->lastInsertId();

                // Liên kết đặt tour với đoàn
                $this->datTour->addBookingToDeparture($booking_id, $departure_id, $pax_count);

                $_SESSION['message'] = 'Đã tạo đoàn mới và thêm đặt tour thành công!';
                $_SESSION['message_type'] = 'success';
                
                header("Location: index.php?action=dat_tour_chi_tiet&id=$booking_id");
                exit();

            } catch (Exception $e) {
                $_SESSION['message'] = 'Có lỗi xảy ra: ' . $e->getMessage();
                $_SESSION['message_type'] = 'error';
                header("Location: index.php?action=dat_tour_tao_doan_moi&booking_id=$booking_id");
                exit();
            }
        }
    }
}