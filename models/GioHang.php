<?php
class GioHang
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    public function getGioHangFromUser($id)
    {
        try {
            $sql = 'SELECT * FROM gio_hangs WHERE tai_khoan_id = :tai_khoan_id';

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([':tai_khoan_id' => $id]);

            return $stmt->fetch();
        } catch (Exception $e) {
            echo 'Lỗi' . $e->getMessage();
        }
    }

    public function getSoLuongTonKho($san_pham_id)
    {
        try {
            $sql = 'SELECT so_luong FROM san_phams WHERE id = :san_pham_id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':san_pham_id' => $san_pham_id]);
            return $stmt->fetchColumn(); // Trả về số lượng tồn kho
        } catch (Exception $e) {
            echo 'Lỗi: ' . $e->getMessage();
        }
    }


    public function getDetailGioHang($id)
    {
        try {
            $sql = 'SELECT chi_tiet_gio_hangs.*, san_phams.ten_san_pham ,
                                                 san_phams.hinh_anh,
                                                 san_phams.gia_san_pham, 
                                                 san_phams.gia_khuyen_mai
            FROM chi_tiet_gio_hangs
            INNER JOIN san_phams ON chi_tiet_gio_hangs.san_pham_id = san_phams.id
            WHERE chi_tiet_gio_hangs.gio_hang_id = :gio_hang_id';

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([':gio_hang_id' => $id]);

            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo 'Lỗi' . $e->getMessage();
        }
    }

    public function addGioHang($id)
    {
        try {
            $sql = 'INSERT INTO gio_hangs (tai_khoan_id) VALUE (:id)';

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([':id' => $id]);

            return $this->conn->lastInsertId();
        } catch (Exception $e) {
            echo 'Lỗi' . $e->getMessage();
        }
    }

    // Xóa tất cả chi tiết giỏ hàng
    public function xoaTatCaChiTietGioHang($gioHangId)
    {
        try {
            $sql = "DELETE FROM chi_tiet_gio_hangs WHERE gio_hang_id = :gio_hang_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':gio_hang_id' => $gioHangId]);

            // Kiểm tra số lượng dòng bị ảnh hưởng
            // if ($stmt->rowCount() > 0) {
            //     echo "<script>alert('Đã xóa tất cả chi tiết giỏ hàng');</script>";
            // } else {
            //     echo "<script>alert('Không có chi tiết giỏ hàng để xóa');</script>";
            // }
        } catch (Exception $e) {
            echo "<script>alert('Lỗi khi xóa chi tiết giỏ hàng: " . $e->getMessage() . "');</script>";
        }
    }

    // Xóa giỏ hàng
    public function xoaGioHang($tai_khoan_id)
    {
        try {
            $sql = "DELETE FROM gio_hangs WHERE tai_khoan_id = :tai_khoan_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':tai_khoan_id' => $tai_khoan_id]);

            // Kiểm tra số lượng dòng bị ảnh hưởng
            // if ($stmt->rowCount() > 0) {
            //     echo "<script>alert('Giỏ hàng đã bị xóa');</script>";
            // } else {
            //     echo "<script>alert('Giỏ hàng không tồn tại hoặc đã bị xóa');</script>";
            // }
        } catch (Exception $e) {
            echo "<script>alert('Lỗi khi xóa giỏ hàng: " . $e->getMessage() . "');</script>";
        }
    }


    // Xóa sản phẩm khỏi giỏ hàng
    public function xoaSanPhamTrongGioHang($gioHangId, $sanPhamId)
    {
        $sql = "DELETE FROM chi_tiet_gio_hangs WHERE gio_hang_id = :gio_hang_id AND san_pham_id = :san_pham_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':gio_hang_id' => $gioHangId,
            ':san_pham_id' => $sanPhamId
        ]);
    }

    public function addDetailGioHang($gio_hang_id, $san_pham_id, $so_luong)
    {
        try {
            $sql = 'INSERT INTO chi_tiet_gio_hangs (gio_hang_id, san_pham_id, so_luong) 
            VALUE (:gio_hang_id, :san_pham_id, :so_luong)';

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([
                ':so_luong' => $so_luong,
                ':gio_hang_id' => $gio_hang_id,
                ':san_pham_id' => $san_pham_id
            ]);

            return $this->conn->lastInsertId();
        } catch (Exception $e) {
            echo 'Lỗi' . $e->getMessage();
        }
    }

    // Cập nhật số lượng sản phẩm trong giỏ hàng
    public function updateSoLuong($gio_hang_id, $san_pham_id, $so_luong)
    {
        try {
            $sql = 'UPDATE chi_tiet_gio_hangs 
                SET so_luong = :so_luong 
                WHERE gio_hang_id = :gio_hang_id AND san_pham_id = :san_pham_id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':so_luong' => $so_luong,
                ':gio_hang_id' => $gio_hang_id,
                ':san_pham_id' => $san_pham_id
            ]);
        } catch (Exception $e) {
            echo 'Lỗi: ' . $e->getMessage();
        }
    }


    public function getDiscountByCode($code)
    {
        try {
            $sql = 'SELECT * FROM promo_codes WHERE code = :code AND NOW() BETWEEN start_date AND end_date';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':code' => $code]);
            return $stmt->fetch(); // Trả về thông tin giảm giá nếu mã hợp lệ
        } catch (Exception $e) {
            echo 'Lỗi: ' . $e->getMessage();
        }
    }

    public function applyDiscountToCart($userId, $discountAmount)
    {
        // Lưu số tiền giảm vào session để tính toán tổng giá trị
        $_SESSION['discount_amount'] = $discountAmount;
    }
}
