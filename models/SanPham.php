<?php
class SanPham
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }


    public function getAllSanPham()
    {
        try {
            $sql = 'SELECT san_phams.*, danh_mucs.ten_danh_muc
                    FROM san_phams
                    INNER JOIN danh_mucs ON san_phams.danh_muc_id = danh_mucs.id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            // Log error to a file instead of displaying it directly
            error_log('Lỗi: ' . $e->getMessage());
            return false;
        }
    }

    public function getAllDanhMuc()
    {
        try {
            $sql = 'SELECT * FROM danh_mucs';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            // Log error to a file instead of displaying it directly
            error_log('Lỗi: ' . $e->getMessage());
            return false;
        }
    }

    public function getProductInSearch($key, $id_category, $price_range, $sort)
    {
        $sql = "SELECT * FROM san_phams WHERE 1=1";

        // Lọc theo danh mục
        if ($id_category && $id_category !== 'all') {
            $sql .= " AND danh_muc_id = :id_category";
        }

        // Lọc theo mức giá
        if ($price_range && $price_range !== 'all') {
            [$minPrice, $maxPrice] = explode('-', $price_range);
            $sql .= " AND gia_san_pham BETWEEN :minPrice AND :maxPrice";
        }

        // Sắp xếp
        if ($sort && in_array($sort, ['asc', 'desc'])) {
            $sql .= " ORDER BY gia_san_pham $sort";
        }

        // Chuẩn bị câu lệnh SQL
        $stmt = $this->conn->prepare($sql);

        // Gắn giá trị cho các tham số
        if ($id_category && $id_category !== 'all') {
            $stmt->bindParam(':id_category', $id_category, PDO::PARAM_INT);
        }
        if ($price_range && $price_range !== 'all') {
            $stmt->bindParam(':minPrice', $minPrice, PDO::PARAM_INT);
            $stmt->bindParam(':maxPrice', $maxPrice, PDO::PARAM_INT);
        }

        // Thực thi và trả kết quả
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public function getDetailSanPham($id)
    {
        try {
            $sql = 'SELECT san_phams.*, danh_mucs.ten_danh_muc 
                    FROM san_phams
                    INNER JOIN danh_mucs ON san_phams.danh_muc_id = danh_mucs.id
                    WHERE san_phams.id = :id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            error_log('Lỗi: ' . $e->getMessage());
            return false;
        }
    }

    public function getListAnhSanPham($id)
    {
        try {
            $sql = 'SELECT * FROM hinh_anhs INNER JOIN san_phams as p ON hinh_anhs.san_pham_id = p.id WHERE hinh_anhs.san_pham_id = :id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log('Lỗi: ' . $e->getMessage());
            return false;
        }
    }


    public function getBinhLuanFromSanPham($id)
    {
        try {
            $sql = 'SELECT binh_luans.*, tai_khoans.ho_ten, tai_khoans.anh_dai_dien
                    FROM binh_luans
                    INNER JOIN tai_khoans ON binh_luans.tai_khoan_id = tai_khoans.id
                    WHERE binh_luans.san_pham_id = :id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log('Lỗi: ' . $e->getMessage());
            return false;
        }
    }

    public function getDanhGiaFromSanPham($id)
    {
        try {
            $sql = 'SELECT danh_gias.*, tai_khoans.ho_ten, tai_khoans.anh_dai_dien
                    FROM danh_gias
                    INNER JOIN tai_khoans ON danh_gias.tai_khoan_id = tai_khoans.id
                    WHERE danh_gias.san_pham_id = :id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log('Lỗi: ' . $e->getMessage());
            return false;
        }
    }

    public function getListSanPhamDanhMuc($danh_muc_id)
    {
        try {
            // Use parameterized query to prevent SQL injection
            $sql = 'SELECT san_phams.*, danh_mucs.ten_danh_muc
                    FROM san_phams
                    INNER JOIN danh_mucs ON san_phams.danh_muc_id = danh_mucs.id
                    WHERE san_phams.danh_muc_id = :danh_muc_id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':danh_muc_id' => $danh_muc_id]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log('Lỗi: ' . $e->getMessage());
            return false;
        }
    }
}
