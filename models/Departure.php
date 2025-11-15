<?php

class Departure extends BaseModel 
{
    /**
     * Lấy tất cả đoàn du lịch với thông tin tour
     */
    public function getAllDepartures()
    {
        try {
            $sql = "SELECT d.*, t.id as tour_id, t.name as tour_name, t.tour_code, t.duration, t.base_price,
                           COUNT(db.booking_id) as booking_count
                    FROM departures d
                    LEFT JOIN tour_versions tv ON d.tour_version_id = tv.id
                    LEFT JOIN tours t ON tv.tour_id = t.id
                    LEFT JOIN departure_bookings db ON d.id = db.departure_id
                    GROUP BY d.id
                    ORDER BY d.departure_date DESC";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Departure error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy thông tin đoàn theo ID
     */
    public function getDepartureById($id)
    {
        try {
            $sql = "SELECT d.*, t.id as tour_id, t.name as tour_name, t.tour_code, t.duration, t.base_price,
                           COUNT(db.booking_id) as booking_count
                    FROM departures d
                    LEFT JOIN tour_versions tv ON d.tour_version_id = tv.id
                    LEFT JOIN tours t ON tv.tour_id = t.id
                    LEFT JOIN departure_bookings db ON d.id = db.departure_id
                    WHERE d.id = :id
                    GROUP BY d.id";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Departure error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Tạo đoàn mới
     */
    public function createDeparture($data)
    {
        try {
            // Lấy tour_version_id default cho tour này
            $versionSql = "SELECT id FROM tour_versions WHERE tour_id = :tour_id ORDER BY id DESC LIMIT 1";
            $versionStmt = $this->pdo->prepare($versionSql);
            $versionStmt->execute([':tour_id' => $data['tour_id']]);
            $version = $versionStmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$version) {
                // Tạo tour_version mới nếu chưa có
                $createVersionSql = "INSERT INTO tour_versions (tour_id, version_name, effective_date, status) 
                                   VALUES (:tour_id, 'Default', NOW(), 'Active')";
                $createVersionStmt = $this->pdo->prepare($createVersionSql);
                $createVersionStmt->execute([':tour_id' => $data['tour_id']]);
                $version['id'] = $this->pdo->lastInsertId();
            }
            
            $sql = "INSERT INTO departures (tour_version_id, departure_date, return_date, status, min_pax, max_pax, actual_guide_id) 
                    VALUES (:tour_version_id, :departure_date, :return_date, :status, :min_pax, :max_pax, :actual_guide_id)";
            
            $params = [
                ':tour_version_id' => $version['id'],
                ':departure_date' => $data['departure_date'],
                ':return_date' => $data['return_date'],
                ':status' => $data['status'] ?? 'Scheduled',
                ':min_pax' => intval($data['min_pax'] ?? 1),
                ':max_pax' => intval($data['max_pax'] ?? 50),
                ':actual_guide_id' => $data['actual_guide_id'] ?? null
            ];
            
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute($params);
            
            if ($result) {
                return $this->pdo->lastInsertId();
            }
            return false;
            
        } catch (PDOException $e) {
            error_log("Departure error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cập nhật đoàn
     */
    public function updateDeparture($id, $data)
    {
        try {
            $sql = "UPDATE departures SET 
                        departure_date = :departure_date,
                        return_date = :return_date, 
                        status = :status,
                        min_pax = :min_pax,
                        max_pax = :max_pax,
                        actual_guide_id = :actual_guide_id
                    WHERE id = :id";
            
            $params = [
                ':id' => $id,
                ':departure_date' => $data['departure_date'],
                ':return_date' => $data['return_date'],
                ':status' => $data['status'] ?? 'Scheduled',
                ':min_pax' => intval($data['min_pax'] ?? 1),
                ':max_pax' => intval($data['max_pax'] ?? 50),
                ':actual_guide_id' => $data['actual_guide_id'] ?? null
            ];
            
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);
            
        } catch (PDOException $e) {
            error_log("Departure error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Xóa đoàn
     */
    public function deleteDeparture($id)
    {
        try {
            // Kiểm tra xem có booking nào liên kết không
            $checkSql = "SELECT COUNT(*) as count FROM departure_bookings WHERE departure_id = :id";
            $checkStmt = $this->pdo->prepare($checkSql);
            $checkStmt->execute([':id' => $id]);
            $result = $checkStmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result['count'] > 0) {
                return false; // Không thể xóa vì có booking liên kết
            }
            
            $sql = "DELETE FROM departures WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([':id' => $id]);
            
        } catch (PDOException $e) {
            error_log("Departure error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy tất cả tours để hiển thị trong form
     */
    public function getAllTours()
    {
        try {
            $sql = "SELECT id, name, tour_code, duration, base_price FROM tours ORDER BY name ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Departure error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy bookings của một departure
     */
    public function getDepartureBookings($departure_id)
    {
        try {
            $sql = "SELECT b.*, c.name as customer_name, c.phone as customer_phone, c.email as customer_email,
                           db.pax_count
                    FROM bookings b
                    INNER JOIN departure_bookings db ON b.id = db.booking_id
                    LEFT JOIN customers c ON b.customer_id = c.id  
                    WHERE db.departure_id = :departure_id
                    ORDER BY b.booking_date DESC";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':departure_id' => $departure_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Departure error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Thêm booking vào departure
     */
    public function addBookingToDeparture($departure_id, $booking_data)
    {
        try {
            $this->pdo->beginTransaction();
            
            // Tạo booking code
            $booking_code = 'BK' . date('Ymd') . sprintf('%04d', rand(1000, 9999));
            
            // 1. Tạo booking mới
            $bookingSql = "INSERT INTO bookings (customer_id, booking_code, booking_date, total_amount, status) 
                          VALUES (:customer_id, :booking_code, :booking_date, :total_amount, :status)";
            
            $bookingStmt = $this->pdo->prepare($bookingSql);
            $bookingResult = $bookingStmt->execute([
                ':customer_id' => $booking_data['customer_id'],
                ':booking_code' => $booking_code,
                ':booking_date' => date('Y-m-d H:i:s'),
                ':total_amount' => $booking_data['total_amount'],
                ':status' => $booking_data['status'] ?? 'Pending'
            ]);
            
            if (!$bookingResult) {
                $this->pdo->rollBack();
                return false;
            }
            
            $booking_id = $this->pdo->lastInsertId();
            
            // 2. Liên kết booking với departure
            $linkSql = "INSERT INTO departure_bookings (departure_id, booking_id, pax_count) 
                       VALUES (:departure_id, :booking_id, :pax_count)";
            
            $linkStmt = $this->pdo->prepare($linkSql);
            $linkResult = $linkStmt->execute([
                ':departure_id' => $departure_id,
                ':booking_id' => $booking_id,
                ':pax_count' => $booking_data['pax_count'] ?? 1
            ]);
            
            if (!$linkResult) {
                $this->pdo->rollBack();
                return false;
            }
            
            $this->pdo->commit();
            return $booking_id;
            
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Departure error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Xóa booking khỏi departure
     */
    public function removeBookingFromDeparture($departure_id, $booking_id)
    {
        try {
            $sql = "DELETE FROM departure_bookings WHERE departure_id = :departure_id AND booking_id = :booking_id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':departure_id' => $departure_id,
                ':booking_id' => $booking_id
            ]);
        } catch (PDOException $e) {
            error_log("Departure error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy danh sách customers để chọn trong form
     */
    public function getAllCustomers()
    {
        try {
            $sql = "SELECT id, name, phone, email FROM customers ORDER BY name ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Departure error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy danh sách booking chưa tham gia đoàn nào hoặc có thể tham gia thêm đoàn khác
     */
    public function getAvailableBookings()
    {
        try {
            $sql = "SELECT b.id, b.booking_code, b.total_amount, b.status,
                           c.name as customer_name, c.phone as customer_phone, c.email as customer_email
                    FROM bookings b 
                    LEFT JOIN customers c ON b.customer_id = c.id
                    WHERE b.status IN ('Pending', 'Deposited')
                    ORDER BY b.booking_date DESC";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Departure error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Thêm booking có sẵn vào departure
     */
    public function addExistingBookingToDeparture($departure_id, $booking_id, $pax_count)
    {
        try {
            // Kiểm tra xem booking đã có trong departure này chưa
            $checkSql = "SELECT COUNT(*) FROM departure_bookings 
                        WHERE departure_id = :departure_id AND booking_id = :booking_id";
            $checkStmt = $this->pdo->prepare($checkSql);
            $checkStmt->execute([
                ':departure_id' => $departure_id,
                ':booking_id' => $booking_id
            ]);
            
            if ($checkStmt->fetchColumn() > 0) {
                return false; // Đã tồn tại
            }
            
            // Thêm vào departure_bookings
            $sql = "INSERT INTO departure_bookings (departure_id, booking_id, pax_count) 
                   VALUES (:departure_id, :booking_id, :pax_count)";
            
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':departure_id' => $departure_id,
                ':booking_id' => $booking_id,
                ':pax_count' => $pax_count
            ]);
            
        } catch (PDOException $e) {
            error_log("Departure error: " . $e->getMessage());
            return false;
        }
    }
}
?>