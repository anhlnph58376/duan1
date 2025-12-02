<?php

class AuthController
{
    private $userModel;
    
    public function __construct()
    {
        $this->userModel = new User();
    }
    
    public function login()
    {
        // If already logged in, redirect based on role
        if (isset($_SESSION['user_id'])) {
            $userRole = (int)($_SESSION['user_role'] ?? 0);
            if ($userRole === 2) {
                header('Location: index.php?action=hdv_dashboard');
            } else {
                header('Location: index.php');
            }
            exit;
        }
        
        // Handle POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            
            if (empty($username) || empty($password)) {
                $_SESSION['error'] = 'Vui lòng nhập đầy đủ thông tin';
                require_once PATH_VIEW . 'user_login.php';
                return;
            }
            
            $user = $this->userModel->findByUsername($username);
            
            if (!$user) {
                $_SESSION['error'] = 'Tên đăng nhập không tồn tại';
                require_once PATH_VIEW . 'user_login.php';
                return;
            }
            
            if (!$user['is_active']) {
                $_SESSION['error'] = 'Tài khoản đã bị khóa';
                require_once PATH_VIEW . 'user_login.php';
                return;
            }
            
            if (!password_verify($password, $user['password_hash'])) {
                $_SESSION['error'] = 'Mật khẩu không đúng';
                require_once PATH_VIEW . 'user_login.php';
                return;
            }
            
            // Login successful
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['username'];
            $_SESSION['user_role'] = $user['role_id'];
            $_SESSION['tour_guide_id'] = $user['tour_guide_id'];
            
            // Redirect based on role
            if ($user['role_id'] == 2) {
                header('Location: index.php?action=hdv_dashboard');
            } else {
                header('Location: index.php');
            }
            exit;
        }
        
        // Show login form
        require_once PATH_VIEW . 'user_login.php';
    }
    
    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: index.php?action=login');
        exit;
    }
}
