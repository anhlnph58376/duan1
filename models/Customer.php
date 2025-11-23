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

    function addCustomer($name, $phone, $email = null, $address = null, $history_notes = null, $gender = null, $year_of_birth = null, $id_type = null, $id_number = null, $payment_status = null, $personal_requests = null, $checkin_status = null, $room_allocation = null)
    {
        $year_of_birth = ($year_of_birth === '' || $year_of_birth === null) ? null : (int)$year_of_birth;

        $sql = "INSERT INTO customers (name, phone, email, address, history_notes, gender, year_of_birth, id_type, id_number, payment_status, personal_requests, checkin_status, room_allocation)
                VALUES (:name, :phone, :email, :address, :history_notes, :gender, :year_of_birth, :id_type, :id_number, :payment_status, :personal_requests, :checkin_status, :room_allocation)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'address' => $address,
            'history_notes' => $history_notes,
            'gender' => $gender,
            'year_of_birth' => $year_of_birth,
            'id_type' => $id_type,
            'id_number' => $id_number,
            'payment_status' => $payment_status,
            'personal_requests' => $personal_requests,
            'checkin_status' => $checkin_status,
            'room_allocation' => $room_allocation
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

    public function updateCustomer($id, $name, $phone, $email = null, $address = null, $history_notes = null, $gender = null, $year_of_birth = null, $id_type = null, $id_number = null, $payment_status = null, $personal_requests = null, $checkin_status = null, $room_allocation = null)
    {
        $year_of_birth = ($year_of_birth === '' || $year_of_birth === null) ? null : (int)$year_of_birth;

        $fields = [
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'address' => $address,
            'history_notes' => $history_notes,
            'gender' => $gender,
            'year_of_birth' => $year_of_birth,
            'id_type' => $id_type,
            'id_number' => $id_number,
            'payment_status' => $payment_status,
            'personal_requests' => $personal_requests,
            'checkin_status' => $checkin_status,
            'room_allocation' => $room_allocation
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

    public function updateCheckinStatus($id, $checkin_status)
    {
        $sql = "UPDATE customers SET checkin_status = :checkin_status WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id, 'checkin_status' => $checkin_status]);
    }

    public function updateRoomAllocation($id, $room_allocation)
    {
        $sql = "UPDATE customers SET room_allocation = :room_allocation WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id, 'room_allocation' => $room_allocation]);
    }

    public function getCustomersByDeparture($departure_id)
    {
        
        $sql = "SELECT c.* FROM customers c
                JOIN bookings b ON c.id = b.customer_id
                JOIN departure_bookings db ON b.id = db.booking_id
                WHERE db.departure_id = :departure_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['departure_id' => $departure_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteCustomer($id)
    {
        $sql = "DELETE FROM customers WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}