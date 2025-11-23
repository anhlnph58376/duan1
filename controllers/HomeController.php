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
            $id = $_GET['id'];
            $tour = $this->tours->getTourById($id);
            
            $categories = $this->tours->getAllCategories();
            
            // BỔ SUNG: Lấy danh sách ảnh thư viện hiện tại
            $galleryImages = $this->tours->getTourImagesByTourId($id); 
            
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
            $id = $_POST['id'];
            $tour_code = $_POST['tour_code'];

            // 1. Kiểm tra trùng mã tour (trừ chính nó)
            if ($this->tours->checkTourCodeExistsForUpdate($tour_code, $id)) {
                $_SESSION['error_tour_code'] = "Mã tour ($tour_code) đã tồn tại! Vui lòng chọn mã khác.";
                $_SESSION['old_data'] = $_POST;
                header("Location: ?action=tour_edit&id=$id");
                exit();
            }

            // 2. Xử lý upload ảnh chính (nếu có)
            $imagePath = null;
            $file = $_FILES['image'] ?? null;

            if ($file && $file['error'] !== UPLOAD_ERR_NO_FILE) {
                if ($file['error'] !== UPLOAD_ERR_OK) {
                    $_SESSION['error_image'] = "Lỗi upload file ảnh chính: " . $file['error'];
                    $_SESSION['old_data'] = $_POST;
                    header("Location: ?action=tour_edit&id=$id");
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
                    header("Location: ?action=tour_edit&id=$id");
                    exit();
                }
            }
            
            // 3. Xử lý upload Thư viện ảnh (Gallery) MỚI
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
                            $this->tours->addTourImage($id, $target_file); 
                        }
                    }
                }
                // Lưu ý: Không cần thông báo lỗi chi tiết cho từng ảnh gallery, 
                // chỉ cần thông báo thành công chung ở bước 4.
            }

            // 4. Gọi Model cập nhật Tour (bảng tours)
            $result = $this->tours->updateTour($id, $_POST, $imagePath);

            if ($result) {
                $_SESSION['success_message'] = "Cập nhật tour thành công!";
            } else {
                $_SESSION['error_message'] = "Không có dữ liệu nào được cập nhật hoặc xảy ra lỗi SQL.";
            }

            header('Location: ?action=tours');
            exit();
        }
    }

    //                     THÊM TOUR (ADD)

    // Hiển thị form thêm mới
    public function tour_add()
    {
        $categories = $this->tours->getAllCategories();
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
            // Có thể thêm kiểm tra $tour ở đây
            require_once PATH_VIEW . 'tour_detail.php';
        }
    }

    // Return a compact tour schedule/itinerary partial for AJAX/modal
    public function tour_schedule()
    {
        $id = $_GET['id'] ?? 0;
        $tour = $this->tours->getTourById($id);
        if (!$tour) {
            http_response_code(404);
            echo 'Tour không tồn tại';
            return;
        }

        // try to fetch tour versions (if any) and any itinerary column
        $versions = [];
        try {
            $dep = new Departure();
            $pdo = $dep->getPdo();
            $stmt = $pdo->prepare('SELECT * FROM tour_versions WHERE tour_id = :tour_id ORDER BY id DESC');
            $stmt->execute([':tour_id' => $id]);
            $versions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            // ignore - versions optional
        }

        require_once PATH_VIEW . 'tour_schedule_partial.php';
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