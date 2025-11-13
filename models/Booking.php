<?php

class Booking extends BaseModel
{
    // Lấy tất cả booking với thông tin khách hàng
    public function getAllBookings() {
        try {
            $sql = "SELECT b.*, c.name as customer_name, c.phone as customer_phone, c.email as customer_email
                   FROM bookings b 
                   LEFT JOIN customers c ON b.customer_id = c.id
                   ORDER BY b.id DESC";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            // Fallback: chỉ lấy dữ liệu từ bảng bookings
            try {
                $sql = "SELECT * FROM bookings ORDER BY id DESC";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e2) {
                return [];
            }
        }
    }

    // Lấy booking theo ID
    public function getBookingById($id) {
        try {
            $sql = "SELECT b.*, c.name as customer_name, c.phone as customer_phone, c.email as customer_email, c.address as customer_address
                   FROM bookings b 
                   LEFT JOIN customers c ON b.customer_id = c.id
                   WHERE b.id = :id";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            // Fallback: chỉ lấy dữ liệu từ bảng bookings
            try {
                $sql = "SELECT * FROM bookings WHERE id = :id";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute(['id' => $id]);
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e2) {
                return false;
            }
        }
    }

    // Thêm booking mới
    public function addBooking($data) {
        try {
            // Tìm hoặc tạo customer trước
            $customer_id = $this->findOrCreateCustomer($data);
            
            $sql = "INSERT INTO bookings (customer_id, booking_code, booking_date, total_amount, deposit_amount, status) 
                   VALUES (:customer_id, :booking_code, :booking_date, :total_amount, :deposit_amount, :status)";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':customer_id' => $customer_id,
                ':booking_code' => $data['booking_code'] ?? $this->generateBookingCode(),
                ':booking_date' => $data['booking_date'] ?? date('Y-m-d H:i:s'),
                ':total_amount' => $data['total_amount'] ?? 0,
                ':deposit_amount' => $data['deposit_amount'] ?? 0,
                ':status' => $data['status'] ?? 'Pending'
            ]);
            
            return $this->pdo->lastInsertId();
            
        } catch (PDOException $e) {
            throw new Exception("Không thể thêm booking: " . $e->getMessage());
        }
    }

    // Cập nhật booking
    public function updateBooking($id, $data) {
        try {
            // Cập nhật thông tin customer nếu có
            if (isset($data['customer_name']) || isset($data['customer_phone']) || isset($data['customer_email'])) {
                $booking = $this->getBookingById($id);
                if ($booking && $booking['customer_id']) {
                    $this->updateCustomer($booking['customer_id'], $data);
                }
            }
            
            $sql = "UPDATE bookings SET 
                   booking_code = :booking_code,
                   booking_date = :booking_date,
                   total_amount = :total_amount,
                   deposit_amount = :deposit_amount,
                   status = :status
                   WHERE id = :id";
            
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':id' => $id,
                ':booking_code' => $data['booking_code'],
                ':booking_date' => $data['booking_date'],
                ':total_amount' => $data['total_amount'],
                ':deposit_amount' => $data['deposit_amount'],
                ':status' => $data['status']
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Xóa booking
    public function deleteBooking($id) {
        try {
            $sql = "DELETE FROM bookings WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Tạo mã booking tự động
    private function generateBookingCode() {
        return 'BK' . date('Ymd') . rand(1000, 9999);
    }

    // Tìm hoặc tạo customer
    private function findOrCreateCustomer($data) {
        try {
            // Tìm customer theo phone hoặc email
            $sql = "SELECT id FROM customers WHERE phone = :phone OR email = :email LIMIT 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':phone' => $data['customer_phone'] ?? '',
                ':email' => $data['customer_email'] ?? ''
            ]);
            
            $customer = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($customer) {
                return $customer['id'];
            } else {
                // Tạo customer mới
                $sql = "INSERT INTO customers (name, phone, email, address) VALUES (:name, :phone, :email, :address)";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    ':name' => $data['customer_name'] ?? 'Khách hàng',
                    ':phone' => $data['customer_phone'] ?? '',
                    ':email' => $data['customer_email'] ?? '',
                    ':address' => $data['customer_address'] ?? ''
                ]);
                
                return $this->pdo->lastInsertId();
            }
        } catch (PDOException $e) {
            // Fallback: trả về customer_id mặc định
            return 1;
        }
    }

    // Cập nhật thông tin customer
    private function updateCustomer($customer_id, $data) {
        try {
            $sql = "UPDATE customers SET name = :name, phone = :phone, email = :email, address = :address WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':id' => $customer_id,
                ':name' => $data['customer_name'] ?? '',
                ':phone' => $data['customer_phone'] ?? '',
                ':email' => $data['customer_email'] ?? '',
                ':address' => $data['customer_address'] ?? ''
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Lấy tất cả customers để chọn
    public function getAllCustomers() {
        try {
            $sql = "SELECT * FROM customers ORDER BY name ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Lấy booking theo status
    public function getBookingsByStatus($status) {
        try {
            $sql = "SELECT b.*, c.name as customer_name, c.phone as customer_phone
                   FROM bookings b 
                   LEFT JOIN customers c ON b.customer_id = c.id
                   WHERE b.status = :status
                   ORDER BY b.booking_date DESC";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['status' => $status]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            return [];
        }
    }

    // Thống kê booking
    public function getBookingStats() {
        try {
            $sql = "SELECT 
                       status,
                       COUNT(*) as count,
                       SUM(total_amount) as total_amount
                   FROM bookings 
                   GROUP BY status";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            return [];
        }
    }

    // Tính tổng tiền booking (trả về total_amount)
    public function calculateTotalPrice($booking_id) {
        try {
            $sql = "SELECT total_amount FROM bookings WHERE id = :booking_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['booking_id' => $booking_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result ? $result['total_amount'] : 0;
        } catch (PDOException $e) {
            return 0;
        }
    }

    // Cập nhật trạng thái booking
    public function updateBookingStatus($id, $status) {
        try {
            $sql = "UPDATE bookings SET status = :status WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':id' => $id,
                ':status' => $status
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }
}