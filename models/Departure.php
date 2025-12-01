<?php

class Departure extends BaseModel
{
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

            // Kiểm tra sức chứa trước khi tạo booking/link
            $pax_to_add = isset($booking_data['pax_count']) ? (int)$booking_data['pax_count'] : 1;
            $capSql = "SELECT d.max_pax, IFNULL(SUM(db.pax_count),0) as total_pax FROM departures d LEFT JOIN departure_bookings db ON d.id = db.departure_id WHERE d.id = :dep_id GROUP BY d.id";
            $capStmt = $this->pdo->prepare($capSql);
            $capStmt->execute([':dep_id' => $departure_id]);
            $cap = $capStmt->fetch(PDO::FETCH_ASSOC);
            $max = isset($cap['max_pax']) ? (int)$cap['max_pax'] : null;
            $current = isset($cap['total_pax']) ? (int)$cap['total_pax'] : 0;
            if ($max !== null && ($current + $pax_to_add) > $max) {
                $this->pdo->rollBack();
                error_log("Departure error: Not enough capacity on departure {$departure_id}");
                return false;
            }
            
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
     * Lấy danh sách các departure còn chỗ (scheduled, future) kèm theo số lượng booking hiện tại
     * Trả về mảng các departure với khóa: id, departure_date, return_date, max_pax, booking_count, tour_name, tour_id
     */
    public function getAvailableDepartures($tour_id = null, $date = null, $requiredSeats = 0)
    {
        try {
            $sql = "SELECT d.id, d.departure_date, d.return_date, d.status, d.min_pax, d.max_pax,
                           t.id as tour_id, t.name as tour_name,
                           IFNULL(COUNT(db.booking_id), 0) as booking_count,
                           IFNULL(SUM(CASE WHEN dr.status = 'reserved' AND (dr.expires_at IS NULL OR dr.expires_at > NOW()) THEN dr.pax_count ELSE 0 END),0) as reserved_count
                    FROM departures d
                    LEFT JOIN tour_versions tv ON d.tour_version_id = tv.id
                    LEFT JOIN tours t ON tv.tour_id = t.id
                    LEFT JOIN departure_bookings db ON d.id = db.departure_id
                    LEFT JOIN departure_reservations dr ON d.id = dr.departure_id";

            $params = [];
            if (!empty($tour_id)) {
                $sql .= " AND tv.tour_id = :tour_id";
                $params[':tour_id'] = $tour_id;
            }
            if (!empty($date)) {
                // allow date to be specific day or datetime; compare by date portion
                $sql .= " AND DATE(d.departure_date) >= DATE(:date)";
                $params[':date'] = date('Y-m-d', strtotime($date));
            }

            $sql .= " GROUP BY d.id";

            if ($requiredSeats > 0) {
                // require that max_pax is null (unlimited) or booking_count + reserved_count + requiredSeats <= max_pax
                $sql .= " HAVING (d.max_pax IS NULL OR ((IFNULL(COUNT(db.booking_id),0) + IFNULL(SUM(CASE WHEN dr.status = 'reserved' AND (dr.expires_at IS NULL OR dr.expires_at > NOW()) THEN dr.pax_count ELSE 0 END),0)) + :requiredSeats) <= d.max_pax)";
                $params[':requiredSeats'] = (int)$requiredSeats;
            } else {
                $sql .= " HAVING (d.max_pax IS NULL OR (IFNULL(COUNT(db.booking_id),0) + IFNULL(SUM(CASE WHEN dr.status = 'reserved' AND (dr.expires_at IS NULL OR dr.expires_at > NOW()) THEN dr.pax_count ELSE 0 END),0)) < d.max_pax)";
            }

            $sql .= " ORDER BY d.departure_date ASC";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
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

            // Kiểm tra sức chứa
            $capSql = "SELECT d.max_pax, IFNULL(SUM(db.pax_count),0) as total_pax FROM departures d LEFT JOIN departure_bookings db ON d.id = db.departure_id WHERE d.id = :dep_id GROUP BY d.id";
            $capStmt = $this->pdo->prepare($capSql);
            $capStmt->execute([':dep_id' => $departure_id]);
            $cap = $capStmt->fetch(PDO::FETCH_ASSOC);
            $max = isset($cap['max_pax']) ? (int)$cap['max_pax'] : null;
            $current = isset($cap['total_pax']) ? (int)$cap['total_pax'] : 0;
            if ($max !== null && ($current + (int)$pax_count) > $max) {
                error_log("Departure error: Not enough capacity to add booking {$booking_id} to departure {$departure_id}");
                return false;
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

    /**
     * Create a temporary reservation (hold) for a booking on a departure
     * returns reservation id on success or false
     */
    public function addDepartureReservation($departure_id, $booking_id, $pax_count, $holdMinutes = 60)
    {
        try {
            // check capacity including existing bookings and active reservations
            $capSql = "SELECT d.max_pax,
                              IFNULL(SUM(db.pax_count),0) as total_pax,
                              IFNULL(SUM(CASE WHEN dr.status = 'reserved' AND (dr.expires_at IS NULL OR dr.expires_at > NOW()) THEN dr.pax_count ELSE 0 END),0) as reserved_pax
                       FROM departures d
                       LEFT JOIN departure_bookings db ON d.id = db.departure_id
                       LEFT JOIN departure_reservations dr ON d.id = dr.departure_id
                       WHERE d.id = :dep_id
                       GROUP BY d.id";
            $stmt = $this->pdo->prepare($capSql);
            $stmt->execute([':dep_id' => $departure_id]);
            $cap = $stmt->fetch(PDO::FETCH_ASSOC);
            $max = isset($cap['max_pax']) ? (int)$cap['max_pax'] : null;
            $total = isset($cap['total_pax']) ? (int)$cap['total_pax'] : 0;
            $reserved = isset($cap['reserved_pax']) ? (int)$cap['reserved_pax'] : 0;

            if ($max !== null && ($total + $reserved + $pax_count) > $max) {
                return false; // not enough space
            }

            $expires = null;
            if ($holdMinutes > 0) {
                $expires = date('Y-m-d H:i:s', strtotime("+{$holdMinutes} minutes"));
            }

            $ins = $this->pdo->prepare("INSERT INTO departure_reservations (departure_id, booking_id, pax_count, reserved_at, expires_at, status) VALUES (:dep,:bid,:pax,NOW(),:exp,'reserved')");
            $ok = $ins->execute([':dep' => $departure_id, ':bid' => $booking_id, ':pax' => $pax_count, ':exp' => $expires]);
            if ($ok) return $this->pdo->lastInsertId();
            return false;
        } catch (PDOException $e) {
            error_log('Departure reservation error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Confirm a reservation: move it to departure_bookings and mark reservation confirmed
     */
    public function confirmReservation($reservation_id)
    {
        try {
            $this->pdo->beginTransaction();
            $stmt = $this->pdo->prepare('SELECT departure_id, booking_id, pax_count, status FROM departure_reservations WHERE id = :id FOR UPDATE');
            $stmt->execute([':id' => $reservation_id]);
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$res || $res['status'] !== 'reserved') { $this->pdo->rollBack(); return false; }

            // check capacity again
            $capSql = "SELECT d.max_pax, IFNULL(SUM(db.pax_count),0) as total_pax FROM departures d LEFT JOIN departure_bookings db ON d.id = db.departure_id WHERE d.id = :dep_id GROUP BY d.id";
            $capStmt = $this->pdo->prepare($capSql);
            $capStmt->execute([':dep_id' => $res['departure_id']]);
            $capRow = $capStmt->fetch(PDO::FETCH_ASSOC);
            $max = isset($capRow['max_pax']) ? (int)$capRow['max_pax'] : null;
            $total = isset($capRow['total_pax']) ? (int)$capRow['total_pax'] : 0;
            if ($max !== null && ($total + (int)$res['pax_count']) > $max) { $this->pdo->rollBack(); return false; }

            // Insert into departure_bookings
            $ins = $this->pdo->prepare('INSERT INTO departure_bookings (departure_id, booking_id, pax_count) VALUES (:dep, :bid, :pax)');
            $ins->execute([':dep' => $res['departure_id'], ':bid' => $res['booking_id'], ':pax' => $res['pax_count']]);

            // mark reservation confirmed
            $upd = $this->pdo->prepare("UPDATE departure_reservations SET status = 'confirmed' WHERE id = :id");
            $upd->execute([':id' => $reservation_id]);

            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            if ($this->pdo->inTransaction()) $this->pdo->rollBack();
            error_log('Confirm reservation error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy các reservation đang ở trạng thái 'reserved' (chưa confirm và chưa expired)
     */
    public function getActiveReservations()
    {
        try {
            $sql = "SELECT dr.*, d.departure_date, d.return_date, t.name as tour_name, b.booking_code, c.name as customer_name, c.phone as customer_phone
                    FROM departure_reservations dr
                    LEFT JOIN departures d ON dr.departure_id = d.id
                    LEFT JOIN tour_versions tv ON d.tour_version_id = tv.id
                    LEFT JOIN tours t ON tv.tour_id = t.id
                    LEFT JOIN bookings b ON dr.booking_id = b.id
                    LEFT JOIN customers c ON b.customer_id = c.id
                    WHERE dr.status = 'reserved' AND (dr.expires_at IS NULL OR dr.expires_at > NOW())
                    ORDER BY dr.reserved_at ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Departure getActiveReservations error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Expire a single reservation by id (mark status = 'expired')
     */
    public function expireReservationById($reservation_id)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE departure_reservations SET status = 'expired' WHERE id = :id AND status = 'reserved'");
            return $stmt->execute([':id' => $reservation_id]);
        } catch (PDOException $e) {
            error_log('Departure expireReservationById error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Expire all reservations that have passed their expires_at
     */
    public function expireDueReservations()
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE departure_reservations SET status = 'expired' WHERE status = 'reserved' AND expires_at IS NOT NULL AND expires_at <= NOW()");
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Departure expireDueReservations error: ' . $e->getMessage());
            return false;
        }
    }
}
?>