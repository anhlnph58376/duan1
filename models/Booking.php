<?php

class Booking extends BaseModel
{
    // Xóa booking theo id
    public function deleteBooking($id)
    {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM bookings WHERE id = :id');
            return $stmt->execute([':id' => $id]);
        } catch (Exception $e) {
            $this->setLastError($e->getMessage());
            return false;
        }
    }
    protected $lastError = '';

    public function getLastError()
    {
        return $this->lastError;
    }

    protected function setLastError($msg)
    {
        $this->lastError = $msg;
        try {
            $logDir = realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'storage') ?: __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'storage';
            $logPathDir = $logDir . DIRECTORY_SEPARATOR . 'logs';
            if (!is_dir($logPathDir)) @mkdir($logPathDir, 0755, true);
            $file = $logPathDir . DIRECTORY_SEPARATOR . 'booking_errors.log';
            $entry = '[' . date('Y-m-d H:i:s') . '] ' . $msg . PHP_EOL;
            @file_put_contents($file, $entry, FILE_APPEND | LOCK_EX);
        } catch (Exception $e) {
            // ignore
        }
    }

    // Return bookings joined with any linked departures and tour info
    public function getAllBookingsWithDepartures()
    {
        try {
            $sql = "SELECT b.*, c.name as customer_name, c.phone as customer_phone, c.email as customer_email,
                           db.departure_id, d.departure_date, d.return_date,
                           COALESCE(t_from_dep.name, t_direct.name) AS tour_name,
                           COALESCE(t_from_dep.id, t_direct.id) AS tour_id,
                           (SELECT COUNT(*) FROM booking_members bm WHERE bm.booking_id = b.id) AS member_count
                    FROM bookings b
                    LEFT JOIN customers c ON b.customer_id = c.id
                    LEFT JOIN departure_bookings db ON b.id = db.booking_id
                    LEFT JOIN departures d ON db.departure_id = d.id
                    LEFT JOIN tour_versions tv ON d.tour_version_id = tv.id
                    LEFT JOIN tours t_from_dep ON tv.tour_id = t_from_dep.id
                    LEFT JOIN tours t_direct ON b.tour_id = t_direct.id
                    ORDER BY b.booking_date DESC";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->setLastError($e->getMessage());
            return [];
        }
    }

    // Booking stats by status
    public function getBookingStats()
    {
        try {
            $sql = "SELECT status, COUNT(*) as count, SUM(total_amount) as total_amount FROM bookings GROUP BY status";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->setLastError($e->getMessage());
            return [];
        }
    }

    // Delegate to Departure model for available departures
    public function getAvailableDepartures()
    {
        if (!class_exists('Departure')) return [];
        try {
            $dep = new Departure();
            return $dep->getAvailableDepartures();
        } catch (Exception $e) {
            $this->setLastError($e->getMessage());
            return [];
        }
    }

    // Return bookings that have at least one member
    public function getBookingsWithMembers()
    {
        try {
                    $sql = "SELECT b.id, b.booking_code, b.booking_date, b.status, b.total_amount,
                              MAX(c.name) AS customer_name, MAX(c.phone) AS customer_phone,
                                  MAX(t.name) AS tour_name,
                                  COUNT(DISTINCT bm.id) AS member_count
                        FROM bookings b
                        LEFT JOIN customers c ON b.customer_id = c.id
                        INNER JOIN booking_members bm ON bm.booking_id = b.id
                        LEFT JOIN tours t ON b.tour_id = t.id
                        GROUP BY b.id
                        ORDER BY b.booking_date DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->setLastError($e->getMessage());
            return [];
        }
    }

    // Create a new booking. Data should include customer info and optionally tour_id.
    public function addBooking($data)
    {
        try {
            $this->pdo->beginTransaction();

            // Find or create customer by phone (preferred) or email
            $customer_id = null;
            if (!empty($data['customer_phone'])) {
                $stmt = $this->pdo->prepare('SELECT id FROM customers WHERE phone = :phone LIMIT 1');
                $stmt->execute([':phone' => $data['customer_phone']]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($row) $customer_id = $row['id'];
            }

            if (!$customer_id && !empty($data['customer_email'])) {
                $stmt = $this->pdo->prepare('SELECT id FROM customers WHERE email = :email LIMIT 1');
                $stmt->execute([':email' => $data['customer_email']]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($row) $customer_id = $row['id'];
            }

            if (!$customer_id) {
                // Some databases in this project have customers.id without AUTO_INCREMENT.
                // Create an id value as MAX(id)+1 to avoid errors when id has no default.
                $cur = $this->pdo->query('SELECT COALESCE(MAX(id),0) + 1 as next_id FROM customers')->fetch(PDO::FETCH_ASSOC);
                $nextId = $cur['next_id'] ?? 1;
                $ins = $this->pdo->prepare('INSERT INTO customers (id, name, email, phone, address) VALUES (:id, :name, :email, :phone, :address)');
                $ins->execute([
                    ':id' => $nextId,
                    ':name' => $data['customer_name'] ?? '',
                    ':email' => $data['customer_email'] ?? null,
                    ':phone' => $data['customer_phone'] ?? null,
                    ':address' => $data['customer_address'] ?? null
                ]);
                $customer_id = $nextId;
            }

            // If an existing customer was found or created, update address if provided
            if (!empty($customer_id) && !empty($data['customer_address'])) {
                try {
                    $upd = $this->pdo->prepare('UPDATE customers SET address = :address WHERE id = :id');
                    $upd->execute([':address' => $data['customer_address'], ':id' => $customer_id]);
                } catch (Exception $e) {
                    // non-fatal: log and continue
                    $this->setLastError('Warning updating customer address: ' . $e->getMessage());
                }
            }

            // Generate booking code
            $booking_code = 'BK' . date('Ymd') . sprintf('%04d', rand(1000, 9999));

            $sql = "INSERT INTO bookings (customer_id, tour_id, booking_code, booking_date, total_amount, deposit_amount, status, created_at, updated_at)
                    VALUES (:customer_id, :tour_id, :booking_code, :booking_date, :total_amount, :deposit_amount, :status, :created_at, :updated_at)";

            $stmt = $this->pdo->prepare($sql);
            $ok = $stmt->execute([
                ':customer_id' => $customer_id,
                ':tour_id' => !empty($data['tour_id']) ? $data['tour_id'] : null,
                ':booking_code' => $booking_code,
                ':booking_date' => !empty($data['booking_date']) ? date('Y-m-d H:i:s', strtotime($data['booking_date'])) : date('Y-m-d H:i:s'),
                ':total_amount' => $data['total_amount'] ?? 0,
                ':deposit_amount' => $data['deposit_amount'] ?? 0,
                ':status' => $data['status'] ?? 'Pending',
                ':created_at' => date('Y-m-d H:i:s'),
                ':updated_at' => date('Y-m-d H:i:s')
            ]);

            if (!$ok) {
                $this->pdo->rollBack();
                $this->setLastError('Không thể lưu booking');
                return false;
            }

            $booking_id = $this->pdo->lastInsertId();
            // If bookings table has additional columns (pax_count, special_requests, booking_type), update them defensively
            try {
                $colStmt = $this->pdo->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'bookings'");
                $colStmt->execute();
                $cols = $colStmt->fetchAll(PDO::FETCH_COLUMN);
                $updateFields = [];
                $params = [':id' => $booking_id];
                if (in_array('pax_count', $cols, true) && isset($data['pax_count'])) {
                    $updateFields[] = 'pax_count = :pax_count';
                    $params[':pax_count'] = (int)$data['pax_count'];
                }
                if (in_array('booking_type', $cols, true) && !empty($data['booking_type'])) {
                    $updateFields[] = 'booking_type = :booking_type';
                    $params[':booking_type'] = $data['booking_type'];
                }
                if (in_array('special_requests', $cols, true) && !empty($data['special_requests'])) {
                    $updateFields[] = 'special_requests = :special_requests';
                    $params[':special_requests'] = $data['special_requests'];
                } elseif (in_array('note', $cols, true) && !empty($data['special_requests'])) {
                    // fallback to note column
                    $updateFields[] = 'note = :special_requests';
                    $params[':special_requests'] = $data['special_requests'];
                }

                if (!empty($updateFields)) {
                    $updSql = 'UPDATE bookings SET ' . implode(', ', $updateFields) . ' WHERE id = :id';
                    $updStmt = $this->pdo->prepare($updSql);
                    $updStmt->execute($params);
                }
            } catch (Exception $e) {
                // non-fatal: log and continue
                $this->setLastError('Warning updating booking extras: ' . $e->getMessage());
            }
            // Nếu có pax_count, thử gán tự động vào một departure có chỗ
            $pax_count = isset($data['pax_count']) ? (int)$data['pax_count'] : 0;
            if ($pax_count > 0 && class_exists('Departure')) {
                try {
                    $depModel = new Departure();
                    $available = $depModel->getAvailableDepartures($data['tour_id'] ?? null, $data['booking_date'] ?? null, $pax_count);
                    $chosen = null;
                    foreach ($available as $dep) {
                        $max = isset($dep['max_pax']) && $dep['max_pax'] !== null ? (int)$dep['max_pax'] : null;
                        $current = isset($dep['booking_count']) ? (int)$dep['booking_count'] : 0;
                        $free = ($max === null) ? PHP_INT_MAX : ($max - $current);
                        if ($free >= $pax_count) { $chosen = $dep; break; }
                    }
                    if ($chosen) {
                        $ok = $depModel->addExistingBookingToDeparture($chosen['id'], $booking_id, $pax_count);
                        if (!$ok) {
                            $this->setLastError('Tự động gán booking vào đoàn thất bại.');
                        }
                    }
                } catch (Exception $e) {
                    $this->setLastError($e->getMessage());
                }
            }

            $this->pdo->commit();
            return $booking_id;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            $this->setLastError($e->getMessage());
            return false;
        }
    }
    public function addMember($booking_id, $data)
    {
        // Nếu có departure_id, tự động gán booking vào đoàn nếu chưa có
        if (isset($data['departure_id']) && !empty($data['departure_id'])) {
            $depId = (int)$data['departure_id'];
            // Kiểm tra xem đã có liên kết chưa
            $checkSql = "SELECT COUNT(*) FROM departure_bookings WHERE departure_id = :dep_id AND booking_id = :booking_id";
            $checkStmt = $this->pdo->prepare($checkSql);
            $checkStmt->execute([':dep_id' => $depId, ':booking_id' => $booking_id]);
            $exists = $checkStmt->fetchColumn();
            if (!$exists) {
                $insertSql = "INSERT INTO departure_bookings (departure_id, booking_id, pax_count) VALUES (:dep_id, :booking_id, 1)";
                $insertStmt = $this->pdo->prepare($insertSql);
                $insertStmt->execute([':dep_id' => $depId, ':booking_id' => $booking_id]);
            }
        }
        try {
            $started = false;
            if ($this->pdo->inTransaction() !== true) {
                $this->pdo->beginTransaction();
                $started = true;
            }
            // support new fields (defensive): only insert columns that actually exist in the DB
            $desiredCols = [
                'booking_id','full_name','age','gender','passport_number','note',
                'payment_status','payment_amount','checkin_status','room_assignment','special_request','created_at'
            ];

            // Fetch actual columns for booking_members in current database
            $colStmt = $this->pdo->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'booking_members'");
            $colStmt->execute();
            $available = $colStmt->fetchAll(PDO::FETCH_COLUMN);

            $insertCols = array_values(array_intersect($desiredCols, $available));

            if (empty($insertCols)) {
                if ($started) $this->pdo->rollBack();
                $this->setLastError('No valid columns found in booking_members table.');
                return false;
            }

            $placeholders = array_map(function($c){ return ':' . $c; }, $insertCols);
            $sql = "INSERT INTO booking_members (" . implode(', ', $insertCols) . ") VALUES (" . implode(', ', $placeholders) . ")";
            $stmt = $this->pdo->prepare($sql);

            // Build params only for the columns we'll insert
            $params = [];
            foreach ($insertCols as $col) {
                switch ($col) {
                    case 'booking_id': $params[':booking_id'] = $booking_id; break;
                    case 'full_name': $params[':full_name'] = $data['full_name'] ?? ''; break;
                    case 'age': $params[':age'] = $data['age'] ?? null; break;
                    case 'gender': $params[':gender'] = $data['gender'] ?? ''; break;
                    case 'passport_number': $params[':passport_number'] = $data['passport_number'] ?? ''; break;
                    case 'note': $params[':note'] = $data['note'] ?? ''; break;
                    case 'payment_status': $params[':payment_status'] = $data['payment_status'] ?? 'unpaid'; break;
                    case 'payment_amount': $params[':payment_amount'] = isset($data['payment_amount']) ? $data['payment_amount'] : 0; break;
                    case 'checkin_status': $params[':checkin_status'] = $data['checkin_status'] ?? 'not_arrived'; break;
                    case 'room_assignment': $params[':room_assignment'] = $data['room_assignment'] ?? null; break;
                    case 'special_request': $params[':special_request'] = $data['special_request'] ?? null; break;
                    case 'created_at': $params[':created_at'] = date('Y-m-d H:i:s'); break;
                    default:
                        $params[':' . $col] = $data[$col] ?? null;
                }
            }

            $stmt->execute($params);

            $memberId = $this->pdo->lastInsertId();

            // Recalculate required pax for this booking (use members count)
            $members = $this->getMembers($booking_id);
            $requiredPax = !empty($members) ? count($members) : 1;

            // If booking already linked to departures, increment pax_count (with simple capacity check)
            $depSql = "SELECT departure_id FROM departure_bookings WHERE booking_id = :booking_id";
            $depStmt = $this->pdo->prepare($depSql);
            $depStmt->execute([':booking_id' => $booking_id]);
            $linked = $depStmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($linked)) {
                // If a specific departure_id was requested, only update that departure
                $targetDep = isset($data['departure_id']) ? (int)$data['departure_id'] : null;
                if ($targetDep) {
                    // check if booking is already linked to this departure
                    $found = false;
                    foreach ($linked as $lnk) {
                        if ((int)$lnk['departure_id'] === $targetDep) { $found = true; break; }
                    }

                    if ($found) {
                        // capacity check for that departure
                        $capSql = "SELECT d.max_pax, IFNULL(SUM(db2.pax_count),0) as total_pax FROM departures d LEFT JOIN departure_bookings db2 ON d.id = db2.departure_id WHERE d.id = :dep_id GROUP BY d.id";
                        $capStmt = $this->pdo->prepare($capSql);
                        $capStmt->execute([':dep_id' => $targetDep]);
                        $cap = $capStmt->fetch(PDO::FETCH_ASSOC);
                        $max = isset($cap['max_pax']) ? (int)$cap['max_pax'] : null;
                        $total = isset($cap['total_pax']) ? (int)$cap['total_pax'] : 0;
                        if ($max !== null && ($total + 1) > $max) {
                            if ($started) $this->pdo->rollBack();
                            $this->setLastError("Không đủ chỗ trên đoàn #{$targetDep} khi thêm thành viên.");
                            return false;
                        }
                        $updSql = "UPDATE departure_bookings SET pax_count = pax_count + 1 WHERE departure_id = :dep_id AND booking_id = :booking_id";
                        $updStmt = $this->pdo->prepare($updSql);
                        $updStmt->execute([':dep_id' => $targetDep, ':booking_id' => $booking_id]);
                    } else {
                        // booking not yet linked to requested departure: try to link with current requiredPax
                        if (class_exists('Departure')) {
                            try {
                                $depModel = new Departure();
                                $ok = $depModel->addExistingBookingToDeparture($targetDep, $booking_id, $requiredPax);
                                if (!$ok) {
                                    if ($started) $this->pdo->rollBack();
                                    $this->setLastError("Không thể thêm booking vào đoàn #{$targetDep}. Có thể không đủ chỗ hoặc đã tồn tại.");
                                    return false;
                                }
                            } catch (Exception $e) {
                                if ($started) $this->pdo->rollBack();
                                $this->setLastError($e->getMessage());
                                return false;
                            }
                        }
                    }
                } else {
                    // No specific target: update all linked departures (existing behavior)
                    foreach ($linked as $lnk) {
                        // check capacity: total pax on departure + 1 <= max_pax (if max_pax set)
                        $capSql = "SELECT d.max_pax, IFNULL(SUM(db2.pax_count),0) as total_pax FROM departures d LEFT JOIN departure_bookings db2 ON d.id = db2.departure_id WHERE d.id = :dep_id GROUP BY d.id";
                        $capStmt = $this->pdo->prepare($capSql);
                        $capStmt->execute([':dep_id' => $lnk['departure_id']]);
                        $cap = $capStmt->fetch(PDO::FETCH_ASSOC);
                        $max = isset($cap['max_pax']) ? (int)$cap['max_pax'] : null;
                        $total = isset($cap['total_pax']) ? (int)$cap['total_pax'] : 0;
                        if ($max !== null && ($total + 1) > $max) {
                            if ($started) $this->pdo->rollBack();
                            $this->setLastError("Không đủ chỗ trên đoàn #{$lnk['departure_id']} khi thêm thành viên.");
                            return false;
                        }
                        $updSql = "UPDATE departure_bookings SET pax_count = pax_count + 1 WHERE departure_id = :dep_id AND booking_id = :booking_id";
                        $updStmt = $this->pdo->prepare($updSql);
                        $updStmt->execute([':dep_id' => $lnk['departure_id'], ':booking_id' => $booking_id]);
                    }
                }
            } else {
                // Not linked: try to auto-assign to an available departure
                if (class_exists('Departure')) {
                    try {
                        $depModel = new Departure();
                        // Try to respect the booking's tour when auto-assigning
                        $bookingInfo = $this->getBookingById($booking_id);
                        $tourId = $bookingInfo['tour_id'] ?? null;
                        $available = $depModel->getAvailableDepartures($tourId, null, $requiredPax);
                        $chosen = null;
                        foreach ($available as $dep) {
                            $max = isset($dep['max_pax']) && $dep['max_pax'] !== null ? (int)$dep['max_pax'] : null;
                            $current = isset($dep['booking_count']) ? (int)$dep['booking_count'] : 0;
                            $free = ($max === null) ? PHP_INT_MAX : ($max - $current);
                            if ($free >= $requiredPax) { $chosen = $dep; break; }
                        }
                        if ($chosen) {
                            $ok = $depModel->addExistingBookingToDeparture($chosen['id'], $booking_id, $requiredPax);
                            if (!$ok) {
                                $this->setLastError("Tự động gán booking vào đoàn thất bại.");
                            }
                        } else {
                            // fallback: couldn't auto-assign the booking to a departure.
                            // Create a reservation (hold) on the earliest available departure for this tour
                            try {
                                $bookingInfo = $this->getBookingById($booking_id);
                                $tourId = $bookingInfo['tour_id'] ?? null;
                                $fallback = $depModel->getAvailableDepartures($tourId, null, 1);
                                if (!empty($fallback)) {
                                    $depForReserve = $fallback[0];
                                    // Reserve 1 pax (the newly added member) for 60 minutes by default
                                    $resId = $depModel->addDepartureReservation($depForReserve['id'], $booking_id, 1, 60);
                                    if ($resId) {
                                        $this->setLastError("Tạo reservation tạm thời cho booking #{$booking_id} trên đoàn #{$depForReserve['id']} (reservation id: {$resId}).");
                                    } else {
                                        $this->setLastError("Không thể tạo reservation cho booking #{$booking_id} trên đoàn #{$depForReserve['id']}.");
                                    }
                                } else {
                                    $this->setLastError('Không tìm thấy đoàn phù hợp để reservation khi auto-assign thất bại.');
                                }
                            } catch (Exception $e) {
                                $this->setLastError('Fallback reservation error: ' . $e->getMessage());
                            }
                        }
                    } catch (Exception $e) {
                        $this->setLastError($e->getMessage());
                    }
                }
            }

            if ($started) $this->pdo->commit();
            return $memberId;
        } catch (PDOException $e) {
            if (isset($started) && $started) $this->pdo->rollBack();
            $this->setLastError($e->getMessage());
            return false;
        }
    }

    // Update an existing member record (including payment/checkin/room fields)
    public function updateMember($id, $data)
    {
        try {
            $fields = [
                'full_name' => $data['full_name'] ?? null,
                'age' => $data['age'] ?? null,
                'gender' => $data['gender'] ?? null,
                'passport_number' => $data['passport_number'] ?? null,
                'note' => $data['note'] ?? null,
                'payment_status' => $data['payment_status'] ?? null,
                'payment_amount' => isset($data['payment_amount']) ? $data['payment_amount'] : null,
                'checkin_status' => $data['checkin_status'] ?? null,
                'room_assignment' => $data['room_assignment'] ?? null,
                'special_request' => $data['special_request'] ?? null
            ];
            // Ensure we only update columns that actually exist in booking_members
            $colStmt = $this->pdo->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'booking_members'");
            $colStmt->execute();
            $availableCols = $colStmt->fetchAll(PDO::FETCH_COLUMN);
            foreach (array_keys($fields) as $k) {
                if (!in_array($k, $availableCols, true)) {
                    $fields[$k] = null; // will be ignored by update builder
                }
            }
            $set = [];
            $params = [];
            foreach ($fields as $k => $v) {
                if ($v !== null) {
                    $set[] = "$k = :$k";
                    $params[":$k"] = $v;
                }
            }

            if (empty($set)) return true; // nothing to update

            $params[':id'] = $id;
            $sql = "UPDATE booking_members SET " . implode(', ', $set) . " WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            $this->setLastError($e->getMessage());
            return false;
        }
    }

    // Delete a member and adjust departure pax_count if needed
    public function deleteMember($id)
    {
        try {
            $this->pdo->beginTransaction();
            $stmt = $this->pdo->prepare('SELECT booking_id FROM booking_members WHERE id = :id LIMIT 1');
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) { $this->pdo->rollBack(); return false; }
            $booking_id = $row['booking_id'];

            // If booking linked to departures, decrement pax_count
            $depSql = "SELECT departure_id FROM departure_bookings WHERE booking_id = :booking_id";
            $depStmt = $this->pdo->prepare($depSql);
            $depStmt->execute([':booking_id' => $booking_id]);
            $linked = $depStmt->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($linked)) {
                foreach ($linked as $lnk) {
                    $upd = $this->pdo->prepare('UPDATE departure_bookings SET pax_count = GREATEST(0, pax_count - 1) WHERE departure_id = :dep_id AND booking_id = :booking_id');
                    $upd->execute([':dep_id' => $lnk['departure_id'], ':booking_id' => $booking_id]);
                }
            }

            $del = $this->pdo->prepare('DELETE FROM booking_members WHERE id = :id');
            $ok = $del->execute([':id' => $id]);
            $this->pdo->commit();
            return $ok;
        } catch (PDOException $e) {
            if ($this->pdo->inTransaction()) $this->pdo->rollBack();
            $this->setLastError($e->getMessage());
            return false;
        }
    }

    public function getMembers($booking_id)
    {
        try {
            $sql = "SELECT * FROM booking_members WHERE booking_id = :booking_id ORDER BY id ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':booking_id' => $booking_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { return []; }
    }

    public function getBookingById($id)
    {
        try {
            $sql = "SELECT b.*, c.name as customer_name, c.phone as customer_phone, c.email as customer_email, c.address as customer_address FROM bookings b LEFT JOIN customers c ON b.customer_id = c.id WHERE b.id = :id LIMIT 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->setLastError($e->getMessage());
            return false;
        }
    }

    public function getMemberById($id)
    {
        try {
            $sql = "SELECT * FROM booking_members WHERE id = :id LIMIT 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->setLastError($e->getMessage());
            return false;
        }
    }

    // Return all customers (used by booking forms)
    public function getAllCustomers()
    {
        try {
            $sql = "SELECT * FROM customers ORDER BY name ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->setLastError($e->getMessage());
            return [];
        }
    }

    // Update booking and optionally related customer information
    public function updateBooking($id, $data)
    {
        try {
            $this->pdo->beginTransaction();

            $booking = $this->getBookingById($id);
            if (!$booking) {
                $this->pdo->rollBack();
                $this->setLastError('Booking không tồn tại');
                return false;
            }

            // Update customer info if provided
            if (!empty($data['customer_name']) || !empty($data['customer_email']) || !empty($data['customer_phone']) || isset($data['customer_address'])) {
                $custSql = "UPDATE customers SET name = :name, email = :email, phone = :phone, address = :address WHERE id = :id";
                $custStmt = $this->pdo->prepare($custSql);
                $custStmt->execute([
                    ':name' => $data['customer_name'] ?? '',
                    ':email' => $data['customer_email'] ?? null,
                    ':phone' => $data['customer_phone'] ?? null,
                    ':address' => $data['customer_address'] ?? null,
                    ':id' => $booking['customer_id'] ?? 0
                ]);
            }

            // Prepare booking update
            $sql = "UPDATE bookings SET tour_id = :tour_id, booking_code = :booking_code, booking_date = :booking_date, total_amount = :total_amount, deposit_amount = :deposit_amount, status = :status, updated_at = :updated_at WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $ok = $stmt->execute([
                ':tour_id' => !empty($data['tour_id']) ? $data['tour_id'] : null,
                ':booking_code' => $data['booking_code'] ?? $booking['booking_code'],
                ':booking_date' => !empty($data['booking_date']) ? date('Y-m-d H:i:s', strtotime($data['booking_date'])) : $booking['booking_date'],
                ':total_amount' => $data['total_amount'] ?? $booking['total_amount'],
                ':deposit_amount' => $data['deposit_amount'] ?? $booking['deposit_amount'],
                ':status' => $data['status'] ?? $booking['status'],
                ':updated_at' => date('Y-m-d H:i:s'),
                ':id' => $id
            ]);

            if (!$ok) {
                $this->pdo->rollBack();
                $this->setLastError('Không thể cập nhật booking');
                return false;
            }

            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            $this->setLastError($e->getMessage());
            return false;
        }
    }

    // Update only the booking status
    public function updateBookingStatus($id, $status)
    {
        try {
            $allowed = ['Pending','Deposited','Completed','Canceled'];
            // If the status is not one of the known values, still allow it but trim
            $status = trim($status);
            // record old status
            $oldRow = $this->getBookingById($id);
            $oldStatus = $oldRow['status'] ?? null;

            $sql = "UPDATE bookings SET status = :status, updated_at = :updated_at WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $ok = $stmt->execute([
                ':status' => $status,
                ':updated_at' => date('Y-m-d H:i:s'),
                ':id' => $id
            ]);

            if (!$ok) {
                $this->setLastError('Không thể cập nhật trạng thái booking');
                return false;
            }

            // write history if helper exists
            if (function_exists('writeStatusHistory')) {
                writeStatusHistory($id, $oldStatus, $status, $_SESSION['user_name'] ?? null);
            }

            return true;
        } catch (PDOException $e) {
            $this->setLastError($e->getMessage());
            return false;
        }
    }

    // Assign a guide to a booking
    public function assignGuide($booking_id, $guide_id)
    {
        try {
            // First check if the guide_id column exists in the bookings table
            $checkColumn = $this->pdo->prepare("SHOW COLUMNS FROM bookings LIKE 'guide_id'");
            $checkColumn->execute();
            $columnExists = $checkColumn->fetch(PDO::FETCH_ASSOC);

            if ($columnExists) {
                $sql = "UPDATE bookings SET guide_id = :guide_id, updated_at = :updated_at WHERE id = :id";
                $stmt = $this->pdo->prepare($sql);
                return $stmt->execute([
                    ':guide_id' => $guide_id,
                    ':updated_at' => date('Y-m-d H:i:s'),
                    ':id' => $booking_id
                ]);
            } else {
                // If guide_id column doesn't exist, we need to add it
                $alterSql = "ALTER TABLE bookings ADD COLUMN guide_id INT NULL AFTER status";
                $alterStmt = $this->pdo->prepare($alterSql);
                $alterStmt->execute();

                // Now try the update again
                $sql = "UPDATE bookings SET guide_id = :guide_id, updated_at = :updated_at WHERE id = :id";
                $stmt = $this->pdo->prepare($sql);
                return $stmt->execute([
                    ':guide_id' => $guide_id,
                    ':updated_at' => date('Y-m-d H:i:s'),
                    ':id' => $booking_id
                ]);
            }
        } catch (PDOException $e) {
            $this->setLastError($e->getMessage());
            return false;
        }
    }

    /**
     * Get bookings assigned to a specific guide
     */
    public function getBookingsByGuideId($guide_id)
    {
        try {
            $sql = "SELECT b.id, b.booking_code, b.booking_date, b.status,
                           t.name as tour_name, d.departure_date,
                           (SELECT COUNT(*) FROM booking_members WHERE booking_id = b.id) as number_of_guests
                    FROM bookings b
                    LEFT JOIN tours t ON b.tour_id = t.id
                    LEFT JOIN departure_bookings db ON b.id = db.booking_id
                    LEFT JOIN departures d ON db.departure_id = d.id
                    WHERE b.guide_id = :guide_id
                    ORDER BY b.booking_date DESC";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['guide_id' => $guide_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->setLastError($e->getMessage());
            return [];
        }
    }

    // Update booking note (for guide messages)
    public function updateBookingNote($booking_id, $note)
    {
        try {
            // Check if note column exists, if not create it
            $checkColumn = $this->pdo->prepare("SHOW COLUMNS FROM bookings LIKE 'note'");
            $checkColumn->execute();
            $columnExists = $checkColumn->fetch(PDO::FETCH_ASSOC);

            if (!$columnExists) {
                $alterSql = "ALTER TABLE bookings ADD COLUMN note TEXT NULL AFTER status";
                $alterStmt = $this->pdo->prepare($alterSql);
                $alterStmt->execute();
            }

            $sql = "UPDATE bookings SET note = :note, updated_at = :updated_at WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':note' => $note,
                ':updated_at' => date('Y-m-d H:i:s'),
                ':id' => $booking_id
            ]);
        } catch (PDOException $e) {
            $this->setLastError($e->getMessage());
            return false;
        }
    }

}
