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
            $existingGuide = $this->guides->getGuideById($id);
            $user_id = $existingGuide['user_id'] ?? null;
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $email = $_POST['email'] ?? null;
            $license_info = $_POST['license_info'] ?? null;
            $image = $_POST['image'] ?? null;
            $status = $_POST['status'] ?? 'Active';

            $birth_date = $_POST['birth_date'] ?? null;
            $languages = $_POST['languages'] ?? null;
            $certificates = $_POST['certificates'] ?? null;
            $experience_years = $_POST['experience_years'] ?? null;
            $guide_type = $_POST['guide_type'] ?? 'Nội địa';
            $specialization = $_POST['specialization'] ?? null;
            $health_status = $_POST['health_status'] ?? 'Good';
            $performance_rating = $_POST['performance_rating'] ?? null;
            $emergency_contact = $_POST['emergency_contact'] ?? null;
            $address = $_POST['address'] ?? null;
            $join_date = $_POST['join_date'] ?? null;
            $availability_status = $_POST['availability_status'] ?? 'Available';
            $notes = $_POST['notes'] ?? null;

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
                $status,
                $birth_date,
                $languages,
                $certificates,
                $experience_years,
                $guide_type,
                $specialization,
                $health_status,
                $performance_rating,
                $emergency_contact,
                $address,
                $join_date,
                $availability_status,
                $notes
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
            $user_id = null; 
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $email = $_POST['email'] ?? null;
            $license_info = $_POST['license_info'] ?? null;
            $image = $_POST['image'] ?? null;
            $status = $_POST['status'] ?? 'Active';

            $birth_date = $_POST['birth_date'] ?? null;
            $languages = $_POST['languages'] ?? null;
            $certificates = $_POST['certificates'] ?? null;
            $experience_years = $_POST['experience_years'] ?? null;
            $guide_type = $_POST['guide_type'] ?? 'Nội địa';
            $specialization = $_POST['specialization'] ?? null;
            $health_status = $_POST['health_status'] ?? 'Good';
            $performance_rating = $_POST['performance_rating'] ?? null;
            $emergency_contact = $_POST['emergency_contact'] ?? null;
            $address = $_POST['address'] ?? null;
            $join_date = $_POST['join_date'] ?? date('Y-m-d');
            $availability_status = $_POST['availability_status'] ?? 'Available';
            $notes = $_POST['notes'] ?? null;

            if ($this->guides->checkPhoneExists($phone)) {
                $_SESSION['error_phone'] = "Số điện thoại (" . $phone . ") đã tồn tại. Vui lòng chọn số khác!";
                $_SESSION['old_data'] = $_POST;
                header('Location: ?action=guide_add');
                exit();
            }

            $this->guides->addGuide($user_id, $name, $phone, $email, $license_info, $image, $status,
                                   $birth_date, $languages, $certificates, $experience_years,
                                   $guide_type, $specialization, $health_status, $performance_rating,
                                   $emergency_contact, $address, $join_date, $availability_status, $notes);
            $_SESSION['success_message'] = "Thêm hướng dẫn viên thành công!";
            header('Location: ?action=guides');
            exit();
        }
    }

    public function guide_schedule()
    {
        if (isset($_GET['id'])) {
            $guide_id = $_GET['id'];
            $guide = $this->guides->getGuideById($guide_id);

            $start_date = date('Y-m-01');
            $end_date = date('Y-m-t');
            $schedule = $this->guides->getGuideSchedule($guide_id, $start_date, $end_date);

            require_once PATH_VIEW . 'guide_schedule.php';
        } else {
            header('Location: ?action=guides');
        }
    }

    public function update_schedule()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $guide_id = $_POST['guide_id'];
            $schedule_date = $_POST['schedule_date'];
            $status = $_POST['status'];
            $notes = $_POST['notes'] ?? null;

            $existing = $this->guides->getGuideSchedule($guide_id, $schedule_date, $schedule_date);

            if (count($existing) > 0) {
                $this->guides->updateScheduleEntry($existing[0]['id'], $status, $notes);
            } else {
                $this->guides->addScheduleEntry($guide_id, $schedule_date, $status, $notes);
            }

            $_SESSION['success_message'] = "Cập nhật lịch làm việc thành công!";
            header("Location: ?action=guide_schedule&id=$guide_id");
            exit();
        }
    }

    public function update_availability()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $guide_id = $_POST['guide_id'];
            $status = $_POST['availability_status'];

            $this->guides->updateAvailabilityStatus($guide_id, $status);
            $_SESSION['success_message'] = "Cập nhật trạng thái sẵn sàng thành công!";
            header("Location: ?action=guide_detail&id=$guide_id");
            exit();
        }
    }

    public function guide_performance()
    {
        if (isset($_GET['id'])) {
            $guide_id = $_GET['id'];
            $guide = $this->guides->getGuideById($guide_id);
            $performance = $this->guides->getGuidePerformance($guide_id);

            require_once PATH_VIEW . 'guide_performance.php';
        } else {
            header('Location: ?action=guides');
        }
    }

    public function update_health_status()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $guide_id = $_POST['guide_id'];
            $health_status = $_POST['health_status'];
            $notes = $_POST['health_notes'] ?? null;

            $this->guides->updateHealthStatus($guide_id, $health_status, $notes);
            $_SESSION['success_message'] = "Cập nhật tình trạng sức khỏe thành công!";
            header("Location: ?action=guide_detail&id=$guide_id");
            exit();
        }
    }

    public function add_performance_log()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $guide_id = $_POST['guide_id'];
            $rating = $_POST['rating'] ?? null;
            $feedback = $_POST['feedback'] ?? null;
            $log_date = $_POST['log_date'] ?? date('Y-m-d');
            $tour_id = $_POST['tour_id'] ?? null;

            $this->guides->addPerformanceLog($guide_id, $rating, $feedback, $log_date, $tour_id);
            $_SESSION['success_message'] = "Thêm đánh giá hiệu suất thành công!";
            header("Location: ?action=guide_performance&id=$guide_id");
            exit();
        }
    }

    public function guides_by_type()
    {
        $guide_type = $_GET['type'] ?? 'Nội địa';
        $guides = $this->guides->getGuidesByType($guide_type);

        require_once PATH_VIEW . 'guides.php';
    }

    public function guides_by_specialization()
    {
        $specialization = $_GET['specialization'] ?? '';
        $guides = $this->guides->getGuidesBySpecialization($specialization);

        require_once PATH_VIEW . 'guides.php';
    }

    public function guide_dashboard()
    {
        $total_guides = count($this->guides->getAllGuides());
        $available_guides = count($this->guides->getAvailableGuides(date('Y-m-d')));
        $domestic_guides = count($this->guides->getGuidesByType('Nội địa'));
        $international_guides = count($this->guides->getGuidesByType('Quốc tế'));
        $health_concerns = $this->guides->getGuidesWithHealthConcerns();

        require_once PATH_VIEW . 'guide_dashboard.php';
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