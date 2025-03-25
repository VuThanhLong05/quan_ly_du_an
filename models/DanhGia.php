<?php
class DanhGia
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    public function getAllDanhGia()
    {
        try {
            $sql = "INSERT INTO danh_gias (san_pham_id, tai_khoan_id, noi_dung, ngay_dang, trang_thai) 
                    VALUES (:san_pham_id, :tai_khoan_id, :noi_dung, :ngay_dang, :trang_thai)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            // Log error to a file instead of displaying it directly
            error_log('Lỗi: ' . $e->getMessage());
            return false;
        }
    }
}
