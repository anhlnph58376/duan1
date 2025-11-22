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

    function addGuide($user_id, $name, $phone, $email = null, $license_info = null, $image = null, $status = 'Active',
                     $birth_date = null, $languages = null, $certificates = null, $experience_years = null,
                     $guide_type = null, $specialization = null, $health_status = null, $performance_rating = null,
                     $emergency_contact = null, $address = null, $join_date = null, $availability_status = 'Available', $notes = null)
    {
        $sql = "INSERT INTO tour_guides (user_id, name, phone, email, license_info, image, status, birth_date, languages, certificates, experience_years, guide_type, specialization, health_status, performance_rating, emergency_contact, address, join_date, availability_status, notes)
                VALUES (:user_id, :name, :phone, :email, :license_info, :image, :status, :birth_date, :languages, :certificates, :experience_years, :guide_type, :specialization, :health_status, :performance_rating, :emergency_contact, :address, :join_date, :availability_status, :notes)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'user_id' => $user_id,
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'license_info' => $license_info,
            'image' => $image,
            'status' => $status,
            'birth_date' => $birth_date,
            'languages' => $languages,
            'certificates' => $certificates,
            'experience_years' => $experience_years,
            'guide_type' => $guide_type,
            'specialization' => $specialization,
            'health_status' => $health_status,
            'performance_rating' => $performance_rating,
            'emergency_contact' => $emergency_contact,
            'address' => $address,
            'join_date' => $join_date,
            'availability_status' => $availability_status,
            'notes' => $notes
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

    public function updateGuide($id, $user_id, $name, $phone, $email = null, $license_info = null, $image = null, $status = 'Active',
                               $birth_date = null, $languages = null, $certificates = null, $experience_years = null,
                               $guide_type = null, $specialization = null, $health_status = null, $performance_rating = null,
                               $emergency_contact = null, $address = null, $join_date = null, $availability_status = 'Available', $notes = null)
    {
        $fields = [
            'user_id' => $user_id,
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'license_info' => $license_info,
            'image' => $image,
            'status' => $status,
            'birth_date' => $birth_date,
            'languages' => $languages,
            'certificates' => $certificates,
            'experience_years' => $experience_years,
            'guide_type' => $guide_type,
            'specialization' => $specialization,
            'health_status' => $health_status,
            'performance_rating' => $performance_rating,
            'emergency_contact' => $emergency_contact,
            'address' => $address,
            'join_date' => $join_date,
            'availability_status' => $availability_status,
            'notes' => $notes
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

    // Schedule Management Methods (Simplified - using availability_status)
    public function getGuideSchedule($guide_id, $start_date = null, $end_date = null)
    {
        // Since we don't have a separate schedule table, return basic availability info
        $guide = $this->getGuideById($guide_id);
        if (!$guide) return [];

        // Return a simplified schedule structure based on availability_status
        return [
            [
                'id' => 1,
                'guide_id' => $guide_id,
                'schedule_date' => date('Y-m-d'),
                'status' => $guide['availability_status'] ?? 'Available',
                'notes' => 'Current availability status'
            ]
        ];
    }

    public function addScheduleEntry($guide_id, $schedule_date, $status, $notes = null)
    {
        // For now, just update the general availability status
        return $this->updateAvailabilityStatus($guide_id, $status);
    }

    public function updateScheduleEntry($id, $status, $notes = null)
    {
        // Since we don't have individual schedule entries, update general availability
        // This is a simplified approach - in a real system you'd have a schedule table
        return true; // Placeholder
    }

    // Performance Tracking Methods
    public function getGuidePerformance($guide_id)
    {
        // Get performance data from the existing guide_performance_logs table
        $sql = "SELECT
                    COUNT(DISTINCT CASE WHEN tour_id IS NOT NULL THEN tour_id END) as total_tours,
                    AVG(rating) as avg_rating,
                    COUNT(*) as total_logs
                FROM guide_performance_logs
                WHERE guide_id = :guide_id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['guide_id' => $guide_id]);
        $performance = $stmt->fetch(PDO::FETCH_ASSOC);

        // Get recent logs
        $sql_logs = "SELECT * FROM guide_performance_logs
                     WHERE guide_id = :guide_id
                     ORDER BY log_date DESC LIMIT 10";
        $stmt_logs = $this->pdo->prepare($sql_logs);
        $stmt_logs->execute(['guide_id' => $guide_id]);
        $performance['recent_logs'] = $stmt_logs->fetchAll(PDO::FETCH_ASSOC);

        // Get customer count (simplified - assuming each log represents customer interaction)
        $performance['total_customers'] = $performance['total_logs'];

        return $performance;
    }

    public function addPerformanceLog($guide_id, $rating, $feedback, $log_date, $tour_id = null)
    {
        $sql = "INSERT INTO guide_performance_logs (guide_id, tour_id, rating, feedback, log_date)
                VALUES (:guide_id, :tour_id, :rating, :feedback, :log_date)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'guide_id' => $guide_id,
            'tour_id' => $tour_id,
            'rating' => $rating,
            'feedback' => $feedback,
            'log_date' => $log_date
        ]);
    }

    public function getAvailableGuides($date, $guide_type = null)
    {
        $sql = "SELECT g.* FROM tour_guides g
                LEFT JOIN guide_schedules gs ON g.id = gs.guide_id AND gs.schedule_date = :date
                WHERE g.status = 'Active' AND g.availability_status = 'Available'
                AND (gs.status IS NULL OR gs.status = 'Available')";

        $params = ['date' => $date];

        if ($guide_type) {
            $sql .= " AND g.guide_type = :guide_type";
            $params['guide_type'] = $guide_type;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateAvailabilityStatus($guide_id, $status)
    {
        $sql = "UPDATE tour_guides SET availability_status = :status WHERE id = :guide_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'guide_id' => $guide_id,
            'status' => $status
        ]);
    }

    // Classification and Filtering Methods
    public function getGuidesByType($guide_type)
    {
        $sql = "SELECT * FROM tour_guides WHERE guide_type = :guide_type AND status = 'Active'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['guide_type' => $guide_type]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getGuidesBySpecialization($specialization)
    {
        $sql = "SELECT * FROM tour_guides WHERE specialization LIKE :specialization AND status = 'Active'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['specialization' => '%' . $specialization . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Health and Emergency Methods
    public function updateHealthStatus($guide_id, $health_status, $notes = null)
    {
        $sql = "UPDATE tour_guides SET health_status = :health_status, notes = CONCAT(IFNULL(notes, ''), '\nHealth Update: ', :notes) WHERE id = :guide_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'guide_id' => $guide_id,
            'health_status' => $health_status,
            'notes' => $notes
        ]);
    }

    public function getGuidesWithHealthConcerns()
    {
        $sql = "SELECT * FROM tour_guides WHERE health_status NOT IN ('Good', 'Excellent') AND status = 'Active'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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