<?php

class Tours extends BaseModel
{
    function getAlltour()
    {
        $sql = "SELECT * FROM tours";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function getTourById($id)
    {
        $sql = "SELECT * FROM tours WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    function addTour($name, $description, $base_price, $image, $duration, $is_international, $tour_code)
    {
        $sql = "INSERT INTO tours (name, description, base_price, image, duration, is_international, tour_code) 
                VALUES (:name, :description, :base_price, :image, :duration, :is_international, :tour_code)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'name' => $name,
            'description' => $description,
            'base_price' => $base_price,
            'image' => $image,
            'duration' => $duration,
            'is_international' => $is_international,
            'tour_code' => $tour_code
        ]);
    }

    function checkTourCodeExists($tour_code)
    {
        $sql = "SELECT COUNT(*) FROM tours WHERE tour_code = :tour_code";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':tour_code', $tour_code);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }


    public function updateTour($id, $name, $description, $base_price, $image, $duration, $is_international, $tour_code)
    {
        $fields = [
            'name' => $name,
            'description' => $description,
            'base_price' => $base_price,
            'duration' => $duration,
            'is_international' => $is_international,
            'tour_code' => $tour_code
        ];

        if (!empty($image)) {
            $fields['image'] = $image;
        }

        $setClauses = [];
        foreach (array_keys($fields) as $key) {
            $setClauses[] = "$key = :$key";
        }

        $sql = "UPDATE tours SET " . implode(', ', $setClauses) . " WHERE id = :id";
        $fields['id'] = $id;

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($fields);
    }

    public function checkTourCodeExistsForUpdate($tour_code, $id_to_exclude)
    {
        $sql = "SELECT COUNT(*) FROM tours WHERE tour_code = :tour_code AND id != :id_to_exclude";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':tour_code', $tour_code);
        $stmt->bindParam(':id_to_exclude', $id_to_exclude);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function deleteTour($id)
    {
        $sql = "DELETE FROM tours WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

}
