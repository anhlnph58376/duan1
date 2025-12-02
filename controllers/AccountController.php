<?php

class AccountController
{
    public function index()
    {
        try {
            $userModel = new User();
            $users = $userModel->all();
        } catch (Throwable $e) {
            $users = [];
            $_SESSION['error_message'] = 'Lỗi tải danh sách tài khoản: ' . $e->getMessage();
        }

        require_once PATH_VIEW . 'account_management.php';
    }

    public function add()
    {
        $user = null;
        require_once PATH_VIEW . 'account_form.php';
    }

    public function store()
    {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $role_id  = intval($_POST['role_id'] ?? 2); // default HDV

        if ($username === '' || $password === '') {
            $_SESSION['error_message'] = 'Vui lòng nhập đủ tên đăng nhập và mật khẩu';
            header('Location: index.php?action=account_add');
            exit;
        }

        $userModel = new User();
        $userModel->create([
            'username' => $username,
            'password_hash' => password_hash($password, PASSWORD_BCRYPT),
            'role_id' => $role_id,
            'is_active' => 1,
        ]);

        $_SESSION['success_message'] = 'Tạo tài khoản thành công';
        header('Location: index.php?action=account_management');
        exit;
    }

    public function edit()
    {
        $id = intval($_GET['id'] ?? 0);
        $userModel = new User();
        $user = $userModel->find($id);
        if (!$user) {
            $_SESSION['error_message'] = 'Không tìm thấy tài khoản';
            header('Location: index.php?action=account_management');
            exit;
        }
        require_once PATH_VIEW . 'account_form.php';
    }

    public function update()
    {
        $id = intval($_POST['id'] ?? 0);
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $role_id  = intval($_POST['role_id'] ?? 2);

        if ($id <= 0 || $username === '') {
            $_SESSION['error_message'] = 'Thiếu dữ liệu cập nhật';
            header('Location: index.php?action=account_management');
            exit;
        }

        $userModel = new User();
        $data = [
            'username' => $username,
            'role_id' => $role_id,
            'is_active' => intval($_POST['is_active'] ?? 1),
        ];
        
        if ($password) {
            $data['password_hash'] = password_hash($password, PASSWORD_BCRYPT);
        }
        
        $userModel->update($id, $data);

        $_SESSION['success_message'] = 'Cập nhật tài khoản thành công';
        header('Location: index.php?action=account_management');
        exit;
    }

    public function delete()
    {
        $id = intval($_GET['id'] ?? 0);
        $userModel = new User();
        $userModel->delete($id);
        $_SESSION['success_message'] = 'Đã xóa tài khoản';
        header('Location: index.php?action=account_management');
        exit;
    }

    public function toggle_active()
    {
        $id = intval($_GET['id'] ?? 0);
        $userModel = new User();
        $userModel->toggleActive($id);
        $_SESSION['success_message'] = 'Đã cập nhật trạng thái hoạt động';
        header('Location: index.php?action=account_management');
        exit;
    }

    public function profile()
    {
        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['error'] = 'Tài khoản không hợp lệ';
            header('Location: index.php?action=account_management');
            exit;
        }

        $userModel = new User();
        $user = $userModel->find($id);
        if (!$user) {
            $_SESSION['error'] = 'Không tìm thấy tài khoản';
            header('Location: index.php?action=account_management');
            exit;
        }

        require_once PATH_VIEW . 'account_profile.php';
    }

    public function profile_update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=account_management');
            exit;
        }

        $userId = intval($_POST['user_id'] ?? 0);
        if ($userId <= 0) {
            $_SESSION['error'] = 'Tài khoản không hợp lệ';
            header('Location: index.php?action=account_management');
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

        $userModel = new User();
        
        if (!empty($newPassword)) {
            if (empty($currentPassword)) {
                $errors[] = 'Vui lòng nhập mật khẩu hiện tại';
            } else {
                $user = $userModel->find($userId);
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

        $existingUser = $userModel->findByUsername($username);
        if ($existingUser && $existingUser['id'] != $userId) {
            $errors[] = 'Tên đăng nhập đã tồn tại';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: index.php?action=account_profile&id=' . $userId);
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
                header('Location: index.php?action=account_profile&id=' . $userId);
                exit;
            }
            
            if ($_FILES['avatar']['size'] > 5 * 1024 * 1024) {
                $_SESSION['error'] = 'Kích thước file không được vượt quá 5MB';
                header('Location: index.php?action=account_profile&id=' . $userId);
                exit;
            }
            
            $fileName = 'avatar_' . $userId . '_' . time() . '.' . $fileExt;
            $uploadPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadPath)) {
                $imagePath = $uploadPath;
                
                $currentUser = $userModel->find($userId);
                if ($currentUser['image'] && file_exists($currentUser['image'])) {
                    unlink($currentUser['image']);
                }
            } else {
                $_SESSION['error'] = 'Lỗi khi upload ảnh';
                header('Location: index.php?action=account_profile&id=' . $userId);
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

        if ($userModel->update($userId, $data)) {
            $_SESSION['success'] = 'Cập nhật hồ sơ thành công';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật';
        }

        header('Location: index.php?action=account_profile&id=' . $userId);
        exit;
    }
}
