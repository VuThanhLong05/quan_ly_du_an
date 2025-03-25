<?php
class LienHeModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    public function addLienHe($email, $noi_dung, $ngay_tao, $trang_thai)
    {
        $sql = "INSERT INTO `lien_hes` (`email`, `noi_dung`, `ngay_tao`, `trang_thai`) 
                VALUES (:email, :noi_dung, :ngay_tao, :trang_thai)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':noi_dung', $noi_dung);
        $stmt->bindParam(':ngay_tao', $ngay_tao);
        $stmt->bindParam(':trang_thai', $trang_thai);
        return $stmt->execute();
    }
}
