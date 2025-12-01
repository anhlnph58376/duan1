<?php

class Tours extends BaseModel
{
    function getAllTours()
    {
        $sql = "SELECT t.*, c.name as category_name
                FROM tours t
                LEFT JOIN tour_categories c ON t.category_id = c.id
                ORDER BY t.id DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function getTourById($id)
    {
        $sql = "SELECT t.*, c.name as category_name 
                FROM tours t
                LEFT JOIN tour_categories c ON t.category_id = c.id
                WHERE t.id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy tất cả danh mục Tour (Categories)
     * @return array Mảng kết hợp ID => Name của các danh mục
     */
    function getAllCategories()
    {
        $sql = "SELECT id, name FROM tour_categories ORDER BY name ASC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        
        // Trả về mảng có dạng [id => name]
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }

    // =======================================================
    //                    CHỨC NĂNG THÊM MỚI
    // =======================================================
    
    /**
     * Lấy ID cuối cùng được thêm vào cơ sở dữ liệu (sử dụng sau hàm addTour)
     * @return string ID của bản ghi vừa được chèn
     */
    public function getLastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
    
    /**
     * Thêm mới Tour
     * @param array $data Mảng chứa toàn bộ thông tin tour
     * @return bool Trả về TRUE nếu thực hiện thành công
     */
    function addTour($data)
    {
        $sql = "INSERT INTO tours (
                    name, tour_code, category_id, 
                    image, description, duration, 
                    departure_point, destination, departure_date,
                    base_price, price_child, price_infant, is_international,
                    policy_booking, policy_cancellation, policy_refund,
                    included_services, excluded_services
                ) 
                VALUES (
                    :name, :tour_code, :category_id, 
                    :image, :description, :duration, 
                    :departure_point, :destination, :departure_date,
                    :base_price, :price_child, :price_infant, :is_international,
                    :policy_booking, :policy_cancellation, :policy_refund,
                    :included_services, :excluded_services
                )";

        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([
            'name' => $data['name'],
            'tour_code' => $data['tour_code'],
            'category_id' => $data['category_id'] ? $data['category_id'] : null, 
            'image' => $data['image'],
            'description' => $data['description'],
            'duration' => $data['duration'],
            'departure_point' => $data['departure_point'] ?? '',
            'destination' => $data['destination'] ?? '',
            'departure_date' => $data['departure_date'] ? $data['departure_date'] : null,
            'base_price' => $data['base_price'],
            'price_child' => $data['price_child'] ?? 0,
            'price_infant' => $data['price_infant'] ?? 0,
            'is_international' => $data['is_international'] ?? 0,
            'policy_booking' => $data['policy_booking'] ?? '',
            'policy_cancellation' => $data['policy_cancellation'] ?? '',
            'policy_refund' => $data['policy_refund'] ?? '',
            'included_services' => $data['included_services'] ?? '',
            'excluded_services' => $data['excluded_services'] ?? ''
        ]);
    }

    // =======================================================
    //                 CHỨC NĂNG CẬP NHẬT (UPDATE)
    // =======================================================

    /**
     * Cập nhật Tour
     * @param int $id ID của tour cần sửa
     * @param array $data Mảng dữ liệu cần update
     * @param string|null $imagePath Đường dẫn ảnh mới (nếu có upload)
     */
    public function updateTour($id, $data, $imagePath = null)
    {
        // Danh sách các trường cho phép update
        $allowedFields = [
            'name', 'tour_code', 'category_id', 'description', 
            'duration', 
            'departure_point', 'destination', 'departure_date',
            'base_price', 'price_child', 'price_infant', 
            'is_international', 'policy_booking', 'policy_cancellation', 
            'policy_refund', 'included_services', 'excluded_services'
        ];

        $updateFields = [];
        $params = ['id' => $id];

        // Duyệt qua dữ liệu gửi lên, chỉ lấy các trường hợp lệ
        foreach ($allowedFields as $field) {
            if (array_key_exists($field, $data)) {
                $updateFields[] = "$field = :$field";
                
                if (in_array($field, ['category_id']) && empty($data[$field])) {
                    $params[$field] = null;
                } else if ($field == 'departure_date' && empty($data[$field])) {
                    $params[$field] = null;
                } else {
                    $params[$field] = $data[$field];
                }
            }
        }

        // Xử lý riêng ảnh: Chỉ update nếu có ảnh mới
        if (!empty($imagePath)) {
            $updateFields[] = "image = :image";
            $params['image'] = $imagePath;
        }

        if (empty($updateFields)) {
            return false;
        }

        $sql = "UPDATE tours SET " . implode(', ', $updateFields) . " WHERE id = :id";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    // =======================================================
    //                 CHỨC NĂNG KIỂM TRA MÃ TOUR
    // =======================================================

    function checkTourCodeExists($tour_code)
    {
        $sql = "SELECT COUNT(*) FROM tours WHERE tour_code = :tour_code";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['tour_code' => $tour_code]);
        return $stmt->fetchColumn() > 0;
    }

    public function checkTourCodeExistsForUpdate($tour_code, $id_to_exclude)
    {
        $sql = "SELECT COUNT(*) FROM tours WHERE tour_code = :tour_code AND id != :id_to_exclude";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'tour_code' => $tour_code, 
            'id_to_exclude' => $id_to_exclude
        ]);
        return $stmt->fetchColumn() > 0;
    }

    // =======================================================
    //                   CHỨC NĂNG XÓA (DELETE)
    // =======================================================

    public function deleteTour($id)
    {
        // Xóa tour. Các dữ liệu liên quan (tour_images, v.v.) sẽ bị xóa nếu cài ON DELETE CASCADE
        $sql = "DELETE FROM tours WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    //             HÀM QUẢN LÝ THƯ VIỆN ẢNH (TOUR_IMAGES)

    /**
     * Thêm một ảnh vào thư viện của Tour.
     * @param int $tourId ID của Tour
     * @param string $imagePath Đường dẫn file ảnh
     * @return bool
     */
    public function addTourImage($tourId, $imagePath)
    {
        $sql = "INSERT INTO tour_images (tour_id, image_path) VALUES (?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$tourId, $imagePath]);
    }

    /**
     * Lấy tất cả ảnh thư viện của một Tour
     * @param int $tourId ID của Tour
     * @return array Danh sách các ảnh
     */
    public function getTourImagesByTourId($tourId)
    {
        $sql = "SELECT * FROM tour_images WHERE tour_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$tourId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Xóa một ảnh khỏi thư viện Tour
     * @param int $imageId ID của bản ghi trong tour_images
     * @return bool
     */
    public function deleteTourImage($imageId)
    {
        $sql = "DELETE FROM tour_images WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$imageId]);
    }

    public function getSuppliersByTourId($tourId)
    {
        $sql = "SELECT s.* FROM suppliers s
                JOIN tour_suppliers ts ON ts.supplier_id = s.id
                WHERE ts.tour_id = :tour_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['tour_id' => $tourId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function syncSuppliers($tourId, array $supplierIds)
    {
        // normalize
        $supplierIds = array_values(array_filter(array_map('intval', $supplierIds), function($v){ return $v > 0; }));
        $this->pdo->beginTransaction();
        try {
            // delete associations not present anymore
            if (count($supplierIds) > 0) {
                $placeholders = implode(',', array_fill(0, count($supplierIds), '?'));
                $params = array_merge([$tourId], $supplierIds);
                $sql = "DELETE FROM tour_suppliers WHERE tour_id = ? AND supplier_id NOT IN ($placeholders)";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute($params);
            } else {
                $stmt = $this->pdo->prepare("DELETE FROM tour_suppliers WHERE tour_id = :tour_id");
                $stmt->execute(['tour_id' => $tourId]);
            }

            // insert missing relations
            $insertStmt = $this->pdo->prepare("INSERT IGNORE INTO tour_suppliers (tour_id, supplier_id) VALUES (:tour_id, :supplier_id)");
            foreach ($supplierIds as $sId) {
                $insertStmt->execute(['tour_id' => $tourId, 'supplier_id' => $sId]);
            }

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}