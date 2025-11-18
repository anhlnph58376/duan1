<?php

class GuideController
{
    public $guides;
    public function __construct()
    {
        $this->guides = new Guide();
    }

    public function index()
    {
        $guides = $this->guides->getAllGuides();
        require_once PATH_VIEW . 'guides.php';
    }

    public function guide_edit()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $guide = $this->guides->getGuideById($id);
            require_once PATH_VIEW . 'guide_edit.php';
        }
    }

    // Cập nhật guide
    public function updateGuide()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            // Get existing guide to preserve user_id
            $existingGuide = $this->guides->getGuideById($id);
            $user_id = $existingGuide['user_id'] ?? null;
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $email = $_POST['email'] ?? null;
            $license_info = $_POST['license_info'] ?? null;
            $image = $_POST['image'] ?? null;
            $status = $_POST['status'] ?? 'Active';

            if ($this->guides->checkPhoneExistsForUpdate($phone, $id)) {
                $_SESSION['error_phone'] = "Số điện thoại (" . $phone . ") đã tồn tại ở hướng dẫn viên khác! Vui lòng chọn số khác!";
                header("Location: ?action=guide_edit&id=$id");
                exit();
            }

            $this->guides->updateGuide(
                $id,
                $user_id,
                $name,
                $phone,
                $email,
                $license_info,
                $image,
                $status
            );
            $_SESSION['success_message'] = "Cập nhật hướng dẫn viên thành công!";
            header('Location: ?action=guides');
            exit();
        }
    }

    public function guide_add()
    {
        require_once PATH_VIEW . 'guide_add.php';
    }

    function addGuide()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = null; // user_id is set to NULL as it's optional and managed separately
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $email = $_POST['email'] ?? null;
            $license_info = $_POST['license_info'] ?? null;
            $image = $_POST['image'] ?? null;
            $status = $_POST['status'] ?? 'Active';

            if ($this->guides->checkPhoneExists($phone)) {
                $_SESSION['error_phone'] = "Số điện thoại (" . $phone . ") đã tồn tại. Vui lòng chọn số khác!";
                $_SESSION['old_data'] = $_POST;
                header('Location: ?action=guide_add');
                exit();
            }

            $this->guides->addGuide($user_id, $name, $phone, $email, $license_info, $image, $status);
            header('Location: ?action=guides');
            exit();
        }
    }

    public function guide_detail()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $guide = $this->guides->getGuideById($id);
            require_once PATH_VIEW . 'guide_detail.php';
        }
    }

    public function guide_delete()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $this->guides->deleteGuide($id);
            header('Location: ?action=guides');
            exit();
        }
    }
}