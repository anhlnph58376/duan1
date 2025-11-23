<?php

class CustomerController
{
    public $customers;
    public function __construct()
    {
        $this->customers = new Customer();
    }

    public function index()
    {
        $customers = $this->customers->getAllCustomers();
        require_once PATH_VIEW . 'customers.php';
    }

    public function customer_edit()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $customer = $this->customers->getCustomerById($id);
            require_once PATH_VIEW . 'customer_edit.php';
        }
    }

    // Cập nhật customer
    public function updateCustomer()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $email = $_POST['email'] ?? null;
            $address = $_POST['address'] ?? null;
            $history_notes = $_POST['history_notes'] ?? null;
            $gender = $_POST['gender'] ?? null;
            $year_of_birth = $_POST['year_of_birth'] ?? null;
            $id_type = $_POST['id_type'] ?? null;
            $id_number = $_POST['id_number'] ?? null;
            $payment_status = $_POST['payment_status'] ?? null;
            $personal_requests = $_POST['personal_requests'] ?? null;
            $checkin_status = $_POST['checkin_status'] ?? null;
            $room_allocation = $_POST['room_allocation'] ?? null;

            if ($this->customers->checkPhoneExistsForUpdate($phone, $id)) {
                $_SESSION['error_phone'] = "Số điện thoại (" . $phone . ") đã tồn tại ở khách hàng khác! Vui lòng chọn số khác!";
                header("Location: ?action=customer_edit&id=$id");
                exit();
            }

            $this->customers->updateCustomer(
                $id,
                $name,
                $phone,
                $email,
                $address,
                $history_notes,
                $gender,
                $year_of_birth,
                $id_type,
                $id_number,
                $payment_status,
                $personal_requests,
                $checkin_status,
                $room_allocation
            );
            $_SESSION['success_message'] = "Cập nhật khách hàng thành công!";
            header('Location: ?action=customers');
            exit();
        }
    }

    public function customer_add()
    {
        require_once PATH_VIEW . 'customer_add.php';
    }

    function addCustomer()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $email = $_POST['email'] ?? null;
            $address = $_POST['address'] ?? null;
            $history_notes = $_POST['history_notes'] ?? null;
            $gender = $_POST['gender'] ?? null;
            $year_of_birth = $_POST['year_of_birth'] ?? null;
            $id_type = $_POST['id_type'] ?? null;
            $id_number = $_POST['id_number'] ?? null;
            $payment_status = $_POST['payment_status'] ?? null;
            $personal_requests = $_POST['personal_requests'] ?? null;
            $checkin_status = $_POST['checkin_status'] ?? null;
            $room_allocation = $_POST['room_allocation'] ?? null;

            if ($this->customers->checkPhoneExists($phone)) {
                $_SESSION['error_phone'] = "Số điện thoại (" . $phone . ") đã tồn tại. Vui lòng chọn số khác!";
                $_SESSION['old_data'] = $_POST;
                header('Location: ?action=customer_add');
                exit();
            }

            $this->customers->addCustomer($name, $phone, $email, $address, $history_notes, $gender, $year_of_birth, $id_type, $id_number, $payment_status, $personal_requests, $checkin_status, $room_allocation);
            header('Location: ?action=customers');
            exit();
        }
    }

    public function updateCheckinStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['customer_id'];
            $checkin_status = $_POST['checkin_status'];
            $this->customers->updateCheckinStatus($id, $checkin_status);
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        }
    }

    public function updateRoomAllocation()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['customer_id'];
            $room_allocation = $_POST['room_allocation'];
            $this->customers->updateRoomAllocation($id, $room_allocation);
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        }
    }

    public function printGroupList()
    {
        if (isset($_GET['departure_id'])) {
            $departure_id = $_GET['departure_id'];
            $customers = $this->customers->getCustomersByDeparture($departure_id);
        } else {
            $customers = $this->customers->getAllCustomers();
        }

        require_once PATH_VIEW . 'group_list_print.php';
    }

    public function customer_detail()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $customer = $this->customers->getCustomerById($id);
            require_once PATH_VIEW . 'customer_detail.php';
        }
    }

    public function customer_delete()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $this->customers->deleteCustomer($id);
            header('Location: ?action=customers');
            exit();
        }
    }
}