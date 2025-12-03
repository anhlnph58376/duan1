<?php
class HomeController
{
    public $tours;

    public function __construct()
    {
        // Khởi tạo Model Tours
        $this->tours = new Tours();
    }

    public function index()
    {
        // Khởi tạo các models cần thiết
        $bookingModel = new Booking();
        $customerModel = new Customer();
        $departureModel = new Departure();
        
        // Lấy tổng doanh thu từ bookings
        $totalRevenue = 0;
        $allBookings = $bookingModel->getAllBookingsWithDepartures();
        foreach ($allBookings as $booking) {
            if (isset($booking['total_amount']) && is_numeric($booking['total_amount'])) {
                $totalRevenue += $booking['total_amount'];
            }
        }
        
        // Tổng số booking
        $totalBookings = count($allBookings);
        
        // Tổng số khách hàng
        $allCustomers = $customerModel->getAllCustomers();
        $totalCustomers = count($allCustomers);
        
        // Lấy top 5 bookings mới nhất
        $topBookings = array_slice($allBookings, 0, 5);
        
        // Lấy top 5 khách hàng có nhiều booking nhất
        $topCustomers = [];
        $customerBookingCount = [];
        foreach ($allBookings as $booking) {
            $customerId = $booking['customer_id'] ?? 0;
            if ($customerId > 0) {
                if (!isset($customerBookingCount[$customerId])) {
                    $customerBookingCount[$customerId] = 0;
                }
                $customerBookingCount[$customerId]++;
            }
        }
        arsort($customerBookingCount);
        $topCustomerIds = array_slice(array_keys($customerBookingCount), 0, 5, true);
        foreach ($topCustomerIds as $customerId) {
            foreach ($allCustomers as $customer) {
                if ($customer['id'] == $customerId) {
                    $customer['booking_count'] = $customerBookingCount[$customerId];
                    $topCustomers[] = $customer;
                    break;
                }
            }
        }
        
        // Doanh thu theo tháng (2025)
        $monthlyRevenue = array_fill(0, 12, 0);
        foreach ($allBookings as $booking) {
            if (!empty($booking['booking_date']) && isset($booking['total_amount'])) {
                $bookingYear = date('Y', strtotime($booking['booking_date']));
                $bookingMonth = (int)date('m', strtotime($booking['booking_date']));
                if ($bookingYear == '2025' && $bookingMonth >= 1 && $bookingMonth <= 12) {
                    $monthlyRevenue[$bookingMonth - 1] += (float)$booking['total_amount'];
                }
            }
        }
        
        // Thống kê trạng thái booking
        $pieStats = [
            'Pending' => 0,
            'Confirmed' => 0,
            'Completed' => 0,
            'Cancelled' => 0
        ];
        foreach ($allBookings as $booking) {
            $status = $booking['status'] ?? 'Pending';
            if (isset($pieStats[$status])) {
                $pieStats[$status]++;
            }
        }
        // Loại bỏ các trạng thái có giá trị 0
        $pieStats = array_filter($pieStats, function($value) { return $value > 0; });
        if (empty($pieStats)) {
            $pieStats = ['No data' => 1];
        }
        
        // Thống kê bookings
        $bookingStats = [
            'total' => $totalBookings,
            'pending' => 0,
            'confirmed' => 0,
            'completed' => 0,
            'cancelled' => 0
        ];
        foreach ($allBookings as $booking) {
            $status = strtolower($booking['status'] ?? 'pending');
            if (isset($bookingStats[$status])) {
                $bookingStats[$status]++;
            }
        }
        
        // Thống kê departures
        $allDepartures = $departureModel->getAllDepartures();
        $departureStats = [
            'total_departures' => count($allDepartures),
            'total_bookings' => 0,
            'total_guests' => 0,
            'scheduled' => 0,
            'in_progress' => 0,
            'completed' => 0,
            'canceled' => 0
        ];
        
        foreach ($allDepartures as $departure) {
            // Đếm booking theo departure
            $departureStats['total_bookings'] += (int)($departure['booking_count'] ?? 0);
            $departureStats['total_guests'] += (int)($departure['total_guests'] ?? 0);
            
            // Đếm theo trạng thái
            $status = strtolower($departure['status'] ?? 'scheduled');
            if ($status == 'scheduled') $departureStats['scheduled']++;
            elseif ($status == 'in progress' || $status == 'in_progress') $departureStats['in_progress']++;
            elseif ($status == 'completed') $departureStats['completed']++;
            elseif ($status == 'canceled' || $status == 'cancelled') $departureStats['canceled']++;
        }
        
        require_once PATH_VIEW . 'main.php';
    }

    public function tours()
    {
        $tours = $this->tours->getAllTours();
        require_once PATH_VIEW . 'tours.php';
    }

    //                     SỬA TOUR (EDIT)

    // Hiển thị form sửa
    public function tour_edit()
    {
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $tour = $this->tours->getTourById($id);
            $categories = $this->tours->getAllCategories();
            $suppliers = (new Suppliers())->getAllSuppliers(); // Thêm
            $galleryImages = $this->tours->getTourImagesByTourId($id);
            $tourSuppliers = $this->tours->getSuppliersByTourId($id); // Thêm
            
            if (!$tour) {
                $_SESSION['error_message'] = "Không tìm thấy Tour có ID: $id";
                header('Location: ?action=tours');
                exit();
            }
            
            // Truyền $galleryImages vào View
            require_once PATH_VIEW . 'tour_edit.php';
        }
    }

    // Xử lý cập nhật dữ liệu
    public function updateTour()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            $tour_code = $_POST['tour_code'] ?? '';

            // 1. Kiểm tra trùng mã tour
            if ($this->tours->checkTourCodeExistsForUpdate($tour_code, $id)) {
                $_SESSION['error_tour_code'] = "Mã tour ($tour_code) đã tồn tại!";
                header("Location: ?action=tour_edit&id=$id");
                exit();
            }

            // 2. Xử lý xóa ảnh gallery nếu có
            if (isset($_POST['delete_gallery_images']) && is_array($_POST['delete_gallery_images'])) {
                foreach ($_POST['delete_gallery_images'] as $image_id => $should_delete) {
                    if ((int)$should_delete === 1) {
                        $this->tours->deleteTourImage((int)$image_id);
                    }
                }
            }

            // 3. Xử lý upload ảnh gallery mới
            if (isset($_FILES['new_gallery_images'])) {
                $target_dir = 'uploads/tours/';
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }

                foreach ($_FILES['new_gallery_images']['tmp_name'] as $key => $tmp_name) {
                    if (!empty($tmp_name) && $_FILES['new_gallery_images']['error'][$key] === UPLOAD_ERR_OK) {
                        $fileName = time() . '_' . basename($_FILES['new_gallery_images']['name'][$key]);
                        $target_file = $target_dir . $fileName;

                        if (move_uploaded_file($tmp_name, $target_file)) {
                            $this->tours->addTourImage($id, $target_file);
                        }
                    }
                }
            }

            // 4. Xử lý ảnh chính (nếu upload mới)
            $imagePath = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $target_dir = 'uploads/tours/';
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }

                $fileName = time() . '_' . basename($_FILES['image']['name']);
                $target_file = $target_dir . $fileName;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                    $imagePath = $target_file;
                }
            }

            // 5. Cập nhật tour với dữ liệu từ $_POST
            $this->tours->updateTour($id, $_POST, $imagePath);

            // sync suppliers nếu có
            $supplierIds = $_POST['supplier_ids'] ?? [];
            $this->tours->syncSuppliers($id, $supplierIds);

            $_SESSION['success_message'] = "Cập nhật tour thành công!";
            header('Location: ?action=tours');
            exit();
        }
    }

    //                     THÊM TOUR (ADD)

    // Hiển thị form thêm mới
    public function tour_add()
    {
        $categories = $this->tours->getAllCategories();
        $suppliers = (new Suppliers())->getAllSuppliers(); // Thêm
        require_once PATH_VIEW . 'tour_add.php';
    }

    // Xử lý thêm mới
    function addTour()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tour_code = $_POST['tour_code'];

            // 1. Kiểm tra trùng mã tour
            if ($this->tours->checkTourCodeExists($tour_code)) {
                $_SESSION['error_tour_code'] = "Mã tour ($tour_code) đã tồn tại.";
                $_SESSION['old_data'] = $_POST; 
                header('Location: ?action=tour_add');
                exit();
            }

            // 2. Xử lý upload ảnh chính
            $imagePath = ''; 
            $file = $_FILES['image'] ?? null;

            if ($file && $file['error'] !== UPLOAD_ERR_NO_FILE) {
                if ($file['error'] !== UPLOAD_ERR_OK) {
                    $_SESSION['error_image'] = "Lỗi upload file ảnh chính: " . $file['error'];
                    $_SESSION['old_data'] = $_POST; 
                    header('Location: ?action=tour_add');
                    exit();
                }
                
                $target_dir = 'uploads/tours/';
                if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }
                
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $safeFileName = uniqid() . '.' . $ext;
                $target_file = $target_dir . $safeFileName;

                if (move_uploaded_file($file['tmp_name'], $target_file)) {
                    $imagePath = $target_file;
                } else {
                    $_SESSION['error_image'] = "Không thể di chuyển file ảnh chính vào thư mục đích.";
                    $_SESSION['old_data'] = $_POST;
                    header('Location: ?action=tour_add');
                    exit();
                }
            }

            // 3. Chuẩn bị dữ liệu và gọi Model thêm mới (bảng tours)
            $data = $_POST;
            $data['image'] = $imagePath; 
            
            $result = $this->tours->addTour($data);
            
            if ($result) {
                // Lấy ID của tour vừa được thêm để lưu ảnh thư viện
                $newTourId = $this->tours->getLastInsertId(); 

                // sync suppliers nếu có
                $supplierIds = $_POST['supplier_ids'] ?? [];
                if (!empty($supplierIds)) {
                    $this->tours->syncSuppliers($newTourId, $supplierIds);
                }

                // 4. Xử lý upload Thư viện ảnh (Gallery)
                if (isset($_FILES['gallery_images']) && is_array($_FILES['gallery_images']['name'])) {
                    $files = $_FILES['gallery_images'];
                    $gallery_target_dir = 'uploads/tours/gallery/';
                    if (!is_dir($gallery_target_dir)) { mkdir($gallery_target_dir, 0777, true); }

                    for ($i = 0; $i < count($files['name']); $i++) {
                        if ($files['error'][$i] === UPLOAD_ERR_OK) {
                            $ext = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
                            $safeFileName = uniqid('img_') . '.' . $ext;
                            $target_file = $gallery_target_dir . $safeFileName;
                            
                            if (move_uploaded_file($files['tmp_name'][$i], $target_file)) {
                                // Gọi hàm Model để thêm ảnh vào bảng tour_images
                                $this->tours->addTourImage($newTourId, $target_file);
                            }
                        }
                    }
                }

                $_SESSION['success_message'] = "Thêm tour thành công!";
                header('Location: ?action=tours');
                exit();
            } else {
                $_SESSION['error_message'] = "Đã xảy ra lỗi khi thêm tour vào cơ sở dữ liệu.";
                $_SESSION['old_data'] = $_POST;
                header('Location: ?action=tour_add');
                exit();
            }
        }
    }

    //                     XÓA VÀ CHI TIẾT

    public function tour_detail()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $tour = $this->tours->getTourById($id);
            $galleryImages = $this->tours->getTourImagesByTourId($id);
            $tourSuppliers = $this->tours->getSuppliersByTourId($id);
            // Có thể thêm kiểm tra $tour ở đây
            require_once PATH_VIEW . 'tour_detail.php';
        }
    }

    public function tour_delete()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $this->tours->deleteTour($id);
            
            // Thêm thông báo
            $_SESSION['success_message'] = "Xóa tour thành công!";
            header('Location: ?action=tours');
            exit();
        }
    }
    
    //              CHỨC NĂNG XÓA ẢNH THƯ VIỆN RIÊNG
    
    public function deleteTourImage()
    {
        if (isset($_GET['id']) && isset($_GET['tour_id'])) {
            $imageId = $_GET['id'];
            $tourId = $_GET['tour_id'];

            // 1. Lấy thông tin ảnh để xóa file vật lý
            // (Bạn cần bổ sung hàm này vào Model nếu muốn xóa file vật lý)
            // Ví dụ: $imageInfo = $this->tours->getTourImageById($imageId);

            // 2. Xóa bản ghi trong database
            $result = $this->tours->deleteTourImage($imageId);

            if ($result) {
                // (Nếu đã lấy được $imageInfo, thực hiện unlink() tại đây)
                $_SESSION['success_message'] = "Xóa ảnh thư viện thành công!";
            } else {
                $_SESSION['error_message'] = "Không thể xóa ảnh thư viện trong database.";
            }

            // Quay lại trang sửa tour
            header("Location: ?action=tour_edit&id=$tourId");
            exit();
        } else {
             $_SESSION['error_message'] = "Thông tin ảnh không hợp lệ.";
             header('Location: ?action=tours');
             exit();
        }
    }
}