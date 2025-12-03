<?php

class UserController
{
    public function logout()
    {
        // Destroy the session and redirect to login page
        session_start();
        session_destroy();
        header('Location: index.php?action=login');
        exit;
    }

    public function uploadProfileImage()
    {
        // Ensure session is not started multiple times
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header('Location: index.php?action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_image'])) {
            $targetDir = "uploads/profile_images/";
            $fileName = basename($_FILES['profile_image']['name']);
            $targetFilePath = $targetDir . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

            // Allow certain file formats
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            if (in_array(strtolower($fileType), $allowedTypes)) {
                if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetFilePath)) {
                    $userModel = new User();
                    $userModel->updateProfileImage($_SESSION['user']['id'], $fileName);
                    $_SESSION['user']['profile_image'] = $fileName;
                    header('Location: index.php?action=user_profile');
                    exit;
                } else {
                    echo "Có lỗi xảy ra khi tải lên ảnh.";
                }
            } else {
                echo "Chỉ chấp nhận các định dạng JPG, JPEG, PNG, GIF.";
            }
        }
    }

    public function login()
    {
        // Ensure session is not started multiple times
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            // Validate input
            if (empty($username) || empty($password)) {
                $_SESSION['error'] = 'Vui lòng nhập đầy đủ thông tin đăng nhập.';
                header('Location: /login');
                exit;
            }

            // Check user credentials
            $user = User::findByUsername($username);
            if (!$user || !$user->verifyPassword($password)) {
                $_SESSION['error'] = 'Tên đăng nhập hoặc mật khẩu không đúng.';
                header('Location: /login');
                exit;
            }

            // Login successful
            $_SESSION['user_id'] = $user->id;
            $_SESSION['success'] = 'Đăng nhập thành công!';
            header('Location: /dashboard');
            exit;
        }

        // Render login view
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['error']);
        include_once __DIR__ . '/../views/user_login.php';
    }
}
