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

            // Ensure guide ID is properly set for guide users
            if ($user['role_id'] == 2) {
                $this->ensureGuideUserSynchronization($user);
            }

            // Debug: Log the session data for guides
            if ($user['role_id'] == 2) {
                error_log("Guide login: user_id=" . $user['id'] . ", tour_guide_id=" . ($_SESSION['tour_guide_id'] ?? 'null'));
            }

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
    
    /**
     * Ensure that guide users have proper synchronization between user account and guide profile
     */
    private function ensureGuideUserSynchronization($user)
    {
        try {
            $guideModel = new Guide();

            // Check if this user already has a guide record
            $existingGuide = $guideModel->getGuideByUserId($user['id']);

            if ($existingGuide) {
                // User already has a guide record, ensure the user.tour_guide_id is set
                if (empty($user['tour_guide_id']) || $user['tour_guide_id'] != $existingGuide['id']) {
                    $userModel = new User();
                    $userModel->update($user['id'], [
                        'tour_guide_id' => $existingGuide['id']
                    ]);
                }

                // Set the guide ID in session
                $_SESSION['tour_guide_id'] = $existingGuide['id'];

            } else {
                // This guide user doesn't have a guide record yet, create one
                $guideId = $guideModel->addGuide(
                    $user['id'],
                    $user['username'], // name
                    '', // phone - will need to be updated
                    $user['email'] ?? '', // email
                    '', // license_info
                    null, // image
                    'Active', // status
                    null, // birth_date
                    '', // languages
                    '', // certificates
                    null, // experience_years
                    'Nội địa', // guide_type
                    '', // specialization
                    'Good', // health_status
                    null, // performance_rating
                    '', // emergency_contact
                    '', // address
                    date('Y-m-d'), // join_date
                    'Available', // availability_status
                    'Tài khoản hướng dẫn viên mới' // notes
                );

                if ($guideId) {
                    // Update the user record with the new guide ID
                    $userModel = new User();
                    $userModel->update($user['id'], [
                        'tour_guide_id' => $guideId
                    ]);
                    $_SESSION['tour_guide_id'] = $guideId;
                }
            }

        } catch (Exception $e) {
            error_log("Guide synchronization error: " . $e->getMessage());
            // Even if there's an error, try to set session from existing data
            if (empty($_SESSION['tour_guide_id']) && !empty($user['tour_guide_id'])) {
                $_SESSION['tour_guide_id'] = $user['tour_guide_id'];
            }
        }
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: index.php?action=login');
        exit;
    }
}
