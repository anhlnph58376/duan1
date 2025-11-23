<?php
class Suppliers extends BaseModel
{
    public function getAllSuppliers()
    {
        $sql = "SELECT * FROM suppliers";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSupplierById($id)
    {
        $sql = "SELECT * FROM suppliers WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addSupplier($name, $contact_person, $phone, $email, $address, $supplier_type)
    {
        $sql = "INSERT INTO suppliers (name, contact_person, phone, email, address, supplier_type) VALUES (:name, :contact_person, :phone, :email, :address, :supplier_type)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['name' => $name, 'contact_person' => $contact_person, 'phone' => $phone, 'email' => $email, 'address' => $address, 'supplier_type' => $supplier_type]);
        return $this->pdo->lastInsertId();
    }

    public function updateSupplier($id, $name, $contact_person, $phone, $email, $address, $supplier_type)
    {
        $sql = "UPDATE suppliers SET name = :name, contact_person = :contact_person, phone = :phone, email = :email, address = :address, supplier_type = :supplier_type WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id, 'name' => $name, 'contact_person' => $contact_person, 'phone' => $phone, 'email' => $email, 'address' => $address, 'supplier_type' => $supplier_type]);
    }
}

?>