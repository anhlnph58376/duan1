<?php

// Sao chép HomeController vào models/ để tương thích autoload
class HomeController
{
    public $tours;
    public function __construct()
    {
        $this->tours = new Tours();
    }
    public function index()
    {
        require_once PATH_VIEW . 'main.php';
    }

    public function tours()
    {
        $tours = $this->tours->getAlltour();
        require_once PATH_VIEW . 'tours.php';
    }

    public function tour_edit()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $tours = $this->tours->getTourById($id);
            require_once PATH_VIEW . 'tour_edit.php';
        }
    }

    // Trong controllers/HomeController.php
    public function updateTour()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $base_price = $_POST['base_price'];
            $duration = $_POST['duration'];
            $is_international = $_POST['is_international'];
            $tour_code = $_POST['tour_code'];

            // Lấy đường dẫn ảnh cũ
            $image = $_POST['current_image'] ?? '';

            if ($this->tours->checkTourCodeExistsForUpdate($tour_code, $id)) {
                $_SESSION['error_tour_code'] = "Mã tour (" . $tour_code . ") đã tồn tại ở tour khác! Vui lòng chọn mã khác!";
                header("Location: ?action=tour_edit&id=$id");
                exit();
            }

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $image_new = basename($_FILES['image']['name']);
                $target_dir_full = 'uploads/tours/';
                $target_file = $target_dir_full . $image_new;

                if (!is_dir($target_dir_full)) {
                    mkdir($target_dir_full, 0777, true);
                }

                if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                    $image = 'uploads/tours/' . $image_new;
                }
            }

            $this->tours->updateTour(
                $id,
                $name,
                $description,
                $base_price,
                $image,
                $duration,
                $is_international,
                $tour_code
            );
            $_SESSION['success_message'] = "Cập nhật tour thành công!";
            header('Location: ?action=tours');
            exit();
        }
    }

    public function tour_add()
    {
        require_once PATH_VIEW . 'tour_add.php';
    }

    function addTour()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $base_price = $_POST['base_price'];
            $duration = $_POST['duration'];
            $is_international = $_POST['is_international'];

            $tour_code = $_POST['tour_code'];

            if ($this->tours->checkTourCodeExists($tour_code)) {
                $_SESSION['error_tour_code'] = "Mã tour (" . $tour_code . ") đã tồn tại. Vui lòng chọn mã khác!";
                $_SESSION['old_data'] = $_POST;
                header('Location: ?action=tour_add');
                exit();
            }

            $image = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $image = basename($_FILES['image']['name']);
                $target_dir = 'uploads/tours/';
                $target_file = $target_dir . $image;
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
                $image = 'uploads/tours/' . $image;
            }

            $this->tours->addTour($name, $description, $base_price, $image, $duration, $is_international, $tour_code);
            header('Location: ?action=tours');
            exit();
        }
    }

    public function tour_detail()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $tour = $this->tours->getTourById($id);
            require_once PATH_VIEW . 'tour_detail.php';
        }
    }

    public function tour_delete()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $this->tours->deleteTour($id);
            header('Location: ?action=tours');
            exit();
        }
    }
}
