<?php

class Customer extends BaseModel
{
    function getAllCustomers()
    {
        $sql = "SELECT * FROM customers";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function getCustomerById($id)
    {
        $sql = "SELECT * FROM customers WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    function addCustomer($name, $phone, $email = null, $address = null, $history_notes = null)
    {
        $sql = "INSERT INTO customers (name, phone, email, address, history_notes)
                VALUES (:name, :phone, :email, :address, :history_notes)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'address' => $address,
            'history_notes' => $history_notes
        ]);
    }

    function checkPhoneExists($phone)
    {
        $sql = "SELECT COUNT(*) FROM customers WHERE phone = :phone";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':phone', $phone);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function updateCustomer($id, $name, $phone, $email = null, $address = null, $history_notes = null)
    {
        $fields = [
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'address' => $address,
            'history_notes' => $history_notes
        ];

        $setClauses = [];
        foreach (array_keys($fields) as $key) {
            $setClauses[] = "$key = :$key";
        }

        $sql = "UPDATE customers SET " . implode(', ', $setClauses) . " WHERE id = :id";
        $fields['id'] = $id;

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($fields);
    }

    public function checkPhoneExistsForUpdate($phone, $id_to_exclude)
    {
        $sql = "SELECT COUNT(*) FROM customers WHERE phone = :phone AND id != :id_to_exclude";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':id_to_exclude', $id_to_exclude);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function deleteCustomer($id)
    {
        $sql = "DELETE FROM customers WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}