<?php
class TinTuc
{
    private $conn;

    public function __construct()
    {
        $this->conn = connectDB(); // Hàm kết nối database
    }

    // Lấy toàn bộ tin tức
    public function getAllTinTuc()
    {
        $sql = "SELECT * FROM bai_viets WHERE trang_thai = 1 ORDER BY ngay_dang DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy tin tức theo ID
    public function getTinTucById($id)
    {
        $sql = "SELECT * FROM bai_viets WHERE id = :id AND trang_thai = 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
