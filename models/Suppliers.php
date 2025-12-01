<?php
class Suppliers extends BaseModel
{
    public function getAllSuppliers()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM suppliers");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSupplierById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM suppliers WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addSupplier($data)
    {
        $sql = "INSERT INTO suppliers (name, contact_person, phone, email, address, supplier_type) 
                VALUES (:name, :contact_person, :phone, :email, :address, :supplier_type)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':name' => $data['name'],
            ':contact_person' => $data['contact_person'],
            ':phone' => $data['phone'],
            ':email' => $data['email'],
            ':address' => $data['address'],
            ':supplier_type' => $data['supplier_type']
        ]);
    }

    public function updateSupplier($id, $data)
    {
        $fields = [];
        $params = ['id' => $id];

        foreach ($data as $col => $value) {
            $fields[] = "`$col` = :$col";
            $params[$col] = $value;
        }

        $sql = "UPDATE suppliers SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function deleteSupplier($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM suppliers WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}

?>