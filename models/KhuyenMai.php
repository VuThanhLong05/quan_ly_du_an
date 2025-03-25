<?php
class KhuyenMai
{
    private $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    public function getAllKhuyenMai()
    {
        try {
            $sql = 'SELECT * FROM khuyen_mais WHERE trang_thai = 1 ORDER BY ngay_bat_dau DESC';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log('Lỗi: ' . $e->getMessage());
            return false;
        }
    }
    public function validateCoupon($coupon_code)
    {
        try {
            $stmt = $this->conn->prepare(
                "SELECT * FROM khuyen_mais WHERE ma_khuyen_mai = :coupon_code AND trang_thai = 1 AND ngay_ket_thuc >= NOW()"
            );
            $stmt->bindParam(':coupon_code', $coupon_code, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log('Lỗi validateCoupon: ' . $e->getMessage());
            return false;
        }
    }
}
