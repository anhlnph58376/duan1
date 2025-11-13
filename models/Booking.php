<?php

class Booking extends BaseModel
{
    protected $table = 'bookings';

    /**
     * Lấy tất cả booking
     */
    public function getAllBookings()
    {
        $sql = "SELECT b.*, t.name as tour_name, t.tour_code, 
                       u.full_name as user_name, u.email as user_email,
                       g.full_name as guide_name, g.email as guide_email
                FROM bookings b 
                LEFT JOIN tours t ON b.tour_id = t.id 
                LEFT JOIN users u ON b.user_id = u.id
                LEFT JOIN users g ON b.guide_id = g.id
                ORDER BY b.created_at DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Lấy booking theo ID
     */
    public function getBookingById($id)
    {
        $sql = "SELECT b.*, t.name as tour_name, t.tour_code, t.description as tour_description,
                       t.base_price, t.duration, t.is_international,
                       u.full_name as user_name, u.email as user_email, u.phone as user_phone,
                       g.full_name as guide_name, g.email as guide_email, g.phone as guide_phone
                FROM bookings b 
                LEFT JOIN tours t ON b.tour_id = t.id 
                LEFT JOIN users u ON b.user_id = u.id
                LEFT JOIN users g ON b.guide_id = g.id
                WHERE b.id = ?";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Thêm booking mới
     */
    public function addBooking($user_id, $tour_id, $booking_date, $number_of_people, $total_price, $notes = '', $status = 'pending')
    {
        $booking_code = $this->generateBookingCode();
        
        $sql = "INSERT INTO bookings (booking_code, user_id, tour_id, booking_date, number_of_people, total_price, notes, status, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$booking_code, $user_id, $tour_id, $booking_date, $number_of_people, $total_price, $notes, $status]);
    }

    /**
     * Cập nhật booking
     */
    public function updateBooking($id, $user_id, $tour_id, $booking_date, $number_of_people, $total_price, $notes, $status)
    {
        $sql = "UPDATE bookings SET 
                user_id = ?, tour_id = ?, booking_date = ?, number_of_people = ?, 
                total_price = ?, notes = ?, status = ?, updated_at = NOW()
                WHERE id = ?";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$user_id, $tour_id, $booking_date, $number_of_people, $total_price, $notes, $status, $id]);
    }

    /**
     * Xóa booking
     */
    public function deleteBooking($id)
    {
        $sql = "DELETE FROM bookings WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }

    /**
     * Gán hướng dẫn viên cho booking
     */
    public function assignGuide($booking_id, $guide_id)
    {
        $sql = "UPDATE bookings SET guide_id = ?, status = 'assigned', updated_at = NOW() WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$guide_id, $booking_id]);
    }

    /**
     * Cập nhật trạng thái booking
     */
    public function updateStatus($booking_id, $status)
    {
        $sql = "UPDATE bookings SET status = ?, updated_at = NOW() WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$status, $booking_id]);
    }

    /**
     * Lấy danh sách hướng dẫn viên (users với role = 'guide')
     */
    public function getAllGuides()
    {
        $sql = "SELECT id, full_name, email, phone FROM users WHERE role = 'guide' AND status = 'active' ORDER BY full_name";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Lấy danh sách khách hàng
     */
    public function getAllUsers()
    {
        $sql = "SELECT id, full_name, email, phone FROM users WHERE role = 'user' ORDER BY full_name";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Tìm kiếm booking theo nhiều tiêu chí
     */
    public function searchBookings($keyword = '', $status = '', $date_from = '', $date_to = '')
    {
        $sql = "SELECT b.*, t.name as tour_name, t.tour_code, 
                       u.full_name as user_name, u.email as user_email,
                       g.full_name as guide_name
                FROM bookings b 
                LEFT JOIN tours t ON b.tour_id = t.id 
                LEFT JOIN users u ON b.user_id = u.id
                LEFT JOIN users g ON b.guide_id = g.id
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($keyword)) {
            $sql .= " AND (b.booking_code LIKE ? OR u.full_name LIKE ? OR t.name LIKE ?)";
            $params[] = "%$keyword%";
            $params[] = "%$keyword%";
            $params[] = "%$keyword%";
        }
        
        if (!empty($status)) {
            $sql .= " AND b.status = ?";
            $params[] = $status;
        }
        
        if (!empty($date_from)) {
            $sql .= " AND b.booking_date >= ?";
            $params[] = $date_from;
        }
        
        if (!empty($date_to)) {
            $sql .= " AND b.booking_date <= ?";
            $params[] = $date_to;
        }
        
        $sql .= " ORDER BY b.created_at DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Tạo mã booking tự động
     */
    private function generateBookingCode()
    {
        $prefix = 'BK';
        $timestamp = date('Ymd');
        
        // Lấy số thứ tự booking trong ngày
        $sql = "SELECT COUNT(*) as count FROM bookings WHERE DATE(created_at) = CURDATE()";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $count = $stmt->fetch()['count'] + 1;
        
        return $prefix . $timestamp . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Kiểm tra booking code có tồn tại không
     */
    public function checkBookingCodeExists($booking_code)
    {
        $sql = "SELECT COUNT(*) as count FROM bookings WHERE booking_code = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$booking_code]);
        return $stmt->fetch()['count'] > 0;
    }

    /**
     * Lấy thống kê booking theo trạng thái
     */
    public function getBookingStats()
    {
        $sql = "SELECT status, COUNT(*) as count FROM bookings GROUP BY status";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Lấy booking theo user_id
     */
    public function getBookingsByUserId($user_id)
    {
        $sql = "SELECT b.*, t.name as tour_name, t.tour_code, 
                       g.full_name as guide_name, g.phone as guide_phone
                FROM bookings b 
                LEFT JOIN tours t ON b.tour_id = t.id 
                LEFT JOIN users g ON b.guide_id = g.id
                WHERE b.user_id = ?
                ORDER BY b.created_at DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }

    /**
     * Lấy booking theo guide_id
     */
    public function getBookingsByGuideId($guide_id)
    {
        $sql = "SELECT b.*, t.name as tour_name, t.tour_code, 
                       u.full_name as user_name, u.phone as user_phone, u.email as user_email
                FROM bookings b 
                LEFT JOIN tours t ON b.tour_id = t.id 
                LEFT JOIN users u ON b.user_id = u.id
                WHERE b.guide_id = ?
                ORDER BY b.booking_date ASC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$guide_id]);
        return $stmt->fetchAll();
    }
}