<?php

class Guide extends BaseModel
{
    function getAllGuides()
    {
        $sql = "SELECT * FROM tour_guides";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function getGuideById($id)
    {
        $sql = "SELECT * FROM tour_guides WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    function addGuide($user_id, $name, $phone, $email = null, $license_info = null, $image = null, $status = 'Active')
    {
        $sql = "INSERT INTO tour_guides (user_id, name, phone, email, license_info, image, status)
                VALUES (:user_id, :name, :phone, :email, :license_info, :image, :status)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'user_id' => $user_id,
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'license_info' => $license_info,
            'image' => $image,
            'status' => $status
        ]);
    }

    function checkPhoneExists($phone)
    {
        $sql = "SELECT COUNT(*) FROM tour_guides WHERE phone = :phone";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':phone', $phone);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function updateGuide($id, $user_id, $name, $phone, $email = null, $license_info = null, $image = null, $status = 'Active')
    {
        $fields = [
            'user_id' => $user_id,
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'license_info' => $license_info,
            'image' => $image,
            'status' => $status
        ];

        $setClauses = [];
        foreach (array_keys($fields) as $key) {
            $setClauses[] = "$key = :$key";
        }

        $sql = "UPDATE tour_guides SET " . implode(', ', $setClauses) . " WHERE id = :id";
        $fields['id'] = $id;

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($fields);
    }

    public function checkPhoneExistsForUpdate($phone, $id_to_exclude)
    {
        $sql = "SELECT COUNT(*) FROM tour_guides WHERE phone = :phone AND id != :id_to_exclude";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':id_to_exclude', $id_to_exclude);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function deleteGuide($id)
    {
        $sql = "DELETE FROM tour_guides WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}