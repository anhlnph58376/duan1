<?php
class ProfileController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    public function index() {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header('Location: ?action=login');
            exit;
        }
        
        $user = $this->userModel->find($userId);
        if (!$user) {
            $_SESSION['error'] = 'Không tìm thấy thông tin người dùng';
            header('Location: ?action=login');
            exit;
        }
        
        require_once 'views/profile.php';
    }
    
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?action=profile');
            exit;
        }
        
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header('Location: ?action=login');
            exit;
        }
        
        $username = trim($_POST['username'] ?? '');
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        $errors = [];
        
        if (empty($username)) {
            $errors[] = 'Tên đăng nhập không được để trống';
        }
        
        if (!empty($newPassword)) {
            if (empty($currentPassword)) {
                $errors[] = 'Vui lòng nhập mật khẩu hiện tại';
            } else {
                $user = $this->userModel->find($userId);
                if (!password_verify($currentPassword, $user['password_hash'])) {
                    $errors[] = 'Mật khẩu hiện tại không đúng';
                }
            }
            
            if (strlen($newPassword) < 6) {
                $errors[] = 'Mật khẩu mới phải có ít nhất 6 ký tự';
            }
            
            if ($newPassword !== $confirmPassword) {
                $errors[] = 'Xác nhận mật khẩu không khớp';
            }
        }
        
        $existingUser = $this->userModel->findByUsername($username);
        if ($existingUser && $existingUser['id'] != $userId) {
            $errors[] = 'Tên đăng nhập đã tồn tại';
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: ?action=profile');
            exit;
        }
        
        $imagePath = null;
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/avatars/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $fileExt = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
            $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
            
            if (!in_array($fileExt, $allowedExts)) {
                $_SESSION['error'] = 'Chỉ chấp nhận file ảnh (jpg, jpeg, png, gif)';
                header('Location: ?action=profile');
                exit;
            }
            
            if ($_FILES['avatar']['size'] > 5 * 1024 * 1024) {
                $_SESSION['error'] = 'Kích thước file không được vượt quá 5MB';
                header('Location: ?action=profile');
                exit;
            }
            
            $fileName = 'avatar_' . $userId . '_' . time() . '.' . $fileExt;
            $uploadPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadPath)) {
                $imagePath = $uploadPath;
                
                $currentUser = $this->userModel->find($userId);
                if ($currentUser['image'] && file_exists($currentUser['image'])) {
                    unlink($currentUser['image']);
                }
            } else {
                $_SESSION['error'] = 'Lỗi khi upload ảnh';
                header('Location: ?action=profile');
                exit;
            }
        }
        
        $data = ['username' => $username];
        
        if (!empty($newPassword)) {
            $data['password_hash'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }
        
        if ($imagePath !== null) {
            $data['image'] = $imagePath;
        }
        
        if ($this->userModel->update($userId, $data)) {
            $_SESSION['user_name'] = $username;
            $_SESSION['success'] = 'Cập nhật thông tin thành công';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật';
        }
        
        header('Location: ?action=profile');
        exit;
    }
}
