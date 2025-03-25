<?php
class BinhLuan
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    // Hàm thêm bình luận
    public function addBinhLuan($san_pham_id, $tai_khoan_id, $noi_dung)
    {
        try {
            $sql = "INSERT INTO binh_luans (san_pham_id, tai_khoan_id, noi_dung, ngay_dang, trang_thai) 
                    VALUES (:san_pham_id, :tai_khoan_id, :noi_dung, :ngay_dang, :trang_thai)";
            $stmt = $this->conn->prepare($sql);

            // Thực thi câu lệnh với dữ liệu
            $stmt->execute([
                ':san_pham_id' => $san_pham_id,
                ':tai_khoan_id' => $tai_khoan_id,
                ':noi_dung' => $noi_dung,
                ':ngay_dang' => date('Y-m-d'), // Ngày đăng hiện tại
                ':trang_thai' => 1, // Mặc định trạng thái là 1 (kích hoạt)
            ]);

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            die("Lỗi: " . $e->getMessage());
        }
    }
}
