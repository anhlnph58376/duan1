<?php
class SuppliersController
{
    private $suppliers;
    public function __construct()
    {
        $this->suppliers = new Suppliers();
    }
    public function suppliers()
    {
        $suppliers = $this->suppliers->getAllSuppliers();
        require_once PATH_VIEW . 'suppliers.php';
    }

    public function supplier_add()
    {
        require_once PATH_VIEW . 'supplier_add.php';
    }

    public function supplier_edit()
    {
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            header('Location: ?action=suppliers');
            exit();
        }
        $id = (int)$_GET['id'];
        $supplier = $this->suppliers->getSupplierById($id);
        if (!$supplier) {
            header('Location: ?action=suppliers');
            exit();
        }
        require_once PATH_VIEW . 'supplier_edit.php';
    }

    public function supplier_delete()
    {
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $id = (int)$_GET['id'];
            $this->suppliers->deleteSupplier($id);
        }
        header('Location: ?action=suppliers');
        exit();
    }

    public function addSupplier()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?action=supplier_add');
            exit();
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $name = trim($_POST['name'] ?? '');
        $contact_person = trim($_POST['contact_person'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $supplier_type = trim($_POST['supplier_type'] ?? '');

        if ($name === '') {
            $_SESSION['error_message'] = 'Tên nhà cung cấp là bắt buộc.';
            $_SESSION['old_data'] = $_POST;
            header('Location: ?action=supplier_add');
            exit();
        }

        $data = [
            'name' => $name,
            'contact_person' => $contact_person,
            'phone' => $phone,
            'email' => $email,
            'address' => $address,
            'supplier_type' => $supplier_type
        ];

        try {
            $this->suppliers->addSupplier($data);
            $_SESSION['success_message'] = 'Thêm nhà cung cấp thành công.';
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Lỗi khi thêm nhà cung cấp: ' . $e->getMessage();
            $_SESSION['old_data'] = $_POST;
        }

        header('Location: ?action=suppliers');
        exit();
    }

    public function updateSupplier()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?action=suppliers');
            exit();
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        if ($id <= 0) {
            $_SESSION['error_message'] = 'ID không hợp lệ.';
            header('Location: ?action=suppliers');
            exit();
        }

        $name = trim($_POST['name'] ?? '');
        $contact_person = trim($_POST['contact_person'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $supplier_type = trim($_POST['supplier_type'] ?? '');

        if ($name === '') {
            $_SESSION['error_message'] = 'Tên nhà cung cấp là bắt buộc.';
            $_SESSION['old_data'] = $_POST;
            header("Location: ?action=supplier_edit&id={$id}");
            exit();
        }

        try {
            if (method_exists($this->suppliers, 'updateSupplier')) {
                $this->suppliers->updateSupplier($id, [
                    'name' => $name,
                    'contact_person' => $contact_person,
                    'phone' => $phone,
                    'email' => $email,
                    'address' => $address,
                    'supplier_type' => $supplier_type
                ]);
            } else {
                $this->suppliers->updateSupplier($id, $name, $contact_person, $phone, $email, $address, $supplier_type);
            }
            $_SESSION['success_message'] = 'Cập nhật nhà cung cấp thành công.';
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Lỗi khi cập nhật nhà cung cấp: ' . $e->getMessage();
            $_SESSION['old_data'] = $_POST;
            header("Location: ?action=supplier_edit&id={$id}");
            exit();
        }

        header('Location: ?action=suppliers');
        exit();
    }
}
?>