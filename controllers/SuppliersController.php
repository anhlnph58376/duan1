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
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $supplier = $this->suppliers->getSupplierById($id);
            require_once PATH_VIEW . 'supplier_edit.php';
        }
    }

    
}

?>