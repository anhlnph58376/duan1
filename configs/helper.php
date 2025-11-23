<?php

if (!function_exists('debug')) {
    function debug($data)
    {
        echo '<pre>';
        print_r($data);
        die;
    }
}

if (!function_exists('writeStatusHistory')) {
    /**
     * Ghi lịch sử thay đổi trạng thái cho một booking.
     * @param PDO $pdo hoặc null để sử dụng DB mặc định từ BaseModel
     */
    function writeStatusHistory($bookingId, $oldStatus, $newStatus, $by = null)
    {
        try {
            // Attempt to get PDO from a global BaseModel instance if available
            if (class_exists('BaseModel')) {
                $bm = new BaseModel();
                $pdo = $bm->getPdo();
            } elseif (isset($GLOBALS['pdo']) && $GLOBALS['pdo'] instanceof PDO) {
                $pdo = $GLOBALS['pdo'];
            } else {
                return false;
            }

            $sql = "INSERT INTO booking_status_history (booking_id, old_status, new_status, changed_by, changed_at) VALUES (:bid, :old, :new, :by, NOW())";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([':bid' => $bookingId, ':old' => $oldStatus, ':new' => $newStatus, ':by' => $by]);
        } catch (Exception $e) {
            error_log('writeStatusHistory error: ' . $e->getMessage());
            return false;
        }
    }
}

if (!function_exists('upload_file')) {
    function upload_file($folder, $file)
    {
        $targetFile = $folder . '/' . time() . '-' . $file["name"];

        if (move_uploaded_file($file["tmp_name"], PATH_ASSETS_UPLOADS . $targetFile)) {
            return $targetFile;
        }

        throw new Exception('Upload file không thành công!');
    }
}