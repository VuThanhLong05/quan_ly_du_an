<?php
class DonHang
{
    private $conn;

    public function __construct()
    {
        $this->conn = connectDB(); // Hàm kết nối database
    }

    public function getDonHangByUserId($tai_khoan_id)
    {
        $sql = "SELECT 
                dh.id AS don_hang_id,
                dh.ma_don_hang,
                dh.ten_nguoi_nhan,
                dh.email_nguoi_nhan,
                dh.sdt_nguoi_nhan,
                dh.dia_chi_nguoi_nhan,
                dh.ngay_dat,
                dh.tong_tien,
                dh.ghi_chu,
                dh.phuong_thuc_thanh_toan_id,
                dh.trang_thai_thanh_toan_id,
                ct.id AS chi_tiet_id,
                ct.don_hang_id,
                ct.san_pham_id,
                ct.don_gia,
                ct.so_luong,
                sp.ten_san_pham,
                sp.gia_san_pham,
                sp.hinh_anh,
                tk.ho_ten,
                 ttt.ten_trang_thai AS trang_thai,
    ttt.id AS trang_thai_id
            FROM don_hangs dh
            JOIN chi_tiet_don_hangs ct ON dh.id = ct.don_hang_id
            INNER JOIN 
    trang_thai_don_hangs ttt ON dh.trang_thai_id = ttt.id
            JOIN san_phams sp ON ct.san_pham_id = sp.id
            JOIN tai_khoans tk ON dh.tai_khoan_id = tk.id
            WHERE dh.tai_khoan_id = :tai_khoan_id
            ORDER BY dh.ngay_dat DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':tai_khoan_id', $tai_khoan_id, PDO::PARAM_INT);
        $stmt->execute();
        $donHangs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($donHangs as $row) {
            $donHangId = $row['don_hang_id'];

            if (!isset($result[$donHangId])) {
                $result[$donHangId] = [
                    'don_hang_id' => $row['don_hang_id'],
                    'ma_don_hang' => $row['ma_don_hang'],
                    'ten_nguoi_nhan' => $row['ten_nguoi_nhan'],
                    'email_nguoi_nhan' => $row['email_nguoi_nhan'],
                    'sdt_nguoi_nhan' => $row['sdt_nguoi_nhan'],
                    'dia_chi_nguoi_nhan' => $row['dia_chi_nguoi_nhan'],
                    'ngay_dat' => $row['ngay_dat'],
                    'tong_tien' => $row['tong_tien'],
                    'ghi_chu' => $row['ghi_chu'],
                    'phuong_thuc_thanh_toan_id' => $row['phuong_thuc_thanh_toan_id'],
                    'trang_thai' => $row['trang_thai'], // Lấy trạng thái từ bảng trang_thai_don_hangs
                    'trang_thai_id' => $row['trang_thai_id'], // Lấy trạng thái từ bảng trang_thai_don_hangs
                    'san_pham' => [],
                    'ho_ten' => $row['ho_ten']  // Lấy tên khách hàng từ bảng tai_khoans
                ];
            }

            // Gán sản phẩm vào mảng 'san_pham' của đơn hàng
            $result[$donHangId]['san_pham'][] = [
                'ten_san_pham' => $row['ten_san_pham'],
                'gia_san_pham' => $row['gia_san_pham'],
                'hinh_anh' => $row['hinh_anh'],
                'so_luong' => $row['so_luong'],
                'don_gia' => $row['don_gia']
            ];
        }

        return $result;
    }



    // Lấy chi tiết đơn hàng theo ID và tài khoản
    public function getChiTietDonHang($don_hang_id)
    {
        $sql = "SELECT 
            dh.ma_don_hang, 
            dh.ngay_dat, 
            ttt.ten_trang_thai AS trang_thai,
            kh.ho_ten, 
            kh.dia_chi, 
            kh.so_dien_thoai, 
            kh.email AS email_nguoi_nhan,  -- Lấy email người nhận
            dh.ghi_chu,  -- Lấy ghi chú
            dh.phuong_thuc_thanh_toan_id, 
            ptth.ten_phuong_thuc AS phuong_thuc_thanh_toan,  -- Lấy tên phương thức thanh toán
            ct.san_pham_id, 
            sp.ten_san_pham, 
            ct.so_luong, 
            sp.gia_san_pham,  
            (ct.so_luong * sp.gia_san_pham) AS thanh_tien,  
            sp.hinh_anh,
            dh.tong_tien,  -- Lấy trường tổng tiền từ bảng don_hangs
            dh.trang_thai_thanh_toan_id ,
            dh.ten_nguoi_nhan -- Lấy trực tiếp trường trạng thái thanh toán ID
        FROM 
            don_hangs AS dh
        INNER JOIN 
            tai_khoans AS kh ON dh.tai_khoan_id = kh.id
        INNER JOIN 
            chi_tiet_don_hangs AS ct ON dh.id = ct.don_hang_id
        INNER JOIN 
            san_phams AS sp ON ct.san_pham_id = sp.id
        INNER JOIN 
            trang_thai_don_hangs AS ttt ON dh.trang_thai_id = ttt.id
        INNER JOIN 
            phuong_thuc_thanh_toans AS ptth ON dh.phuong_thuc_thanh_toan_id = ptth.id
        WHERE 
            dh.id = :don_hang_id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':don_hang_id', $don_hang_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }


    public function huyDonHang($don_hang_id, $tai_khoan_id)
    {
        $sql = "UPDATE don_hangs 
            SET trang_thai_id = 9 -- Đã hủy
            WHERE id = :don_hang_id AND tai_khoan_id = :tai_khoan_id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':don_hang_id', $don_hang_id, PDO::PARAM_INT);
        $stmt->bindParam(':tai_khoan_id', $tai_khoan_id, PDO::PARAM_INT);

        return $stmt->execute();
    }
    public function daNhanDonHang($don_hang_id, $tai_khoan_id)
    {
        $sql = "UPDATE don_hangs 
            SET trang_thai_id = 6 -- Đã nhận
            WHERE id = :don_hang_id AND tai_khoan_id = :tai_khoan_id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':don_hang_id', $don_hang_id, PDO::PARAM_INT);
        $stmt->bindParam(':tai_khoan_id', $tai_khoan_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Phần thanh toán, đặt hàng

    public function addDonHang(
        $tai_khoan_id,
        $ten_nguoi_nhan,
        $email_nguoi_nhan,
        $sdt_nguoi_nhan,
        $dia_chi_nguoi_nhan,
        $ghi_chu,
        $ngay_dat,
        $trang_thai_id,
        $phuong_thuc_thanh_toan_id,
        $tong_tien,
        $ma_don_hang,
        $trang_thai_thanh_toan_id
    ) {
        try {
            $sql = 'INSERT INTO don_hangs (
                    tai_khoan_id,
                    ten_nguoi_nhan,
                    email_nguoi_nhan,
                    sdt_nguoi_nhan,
                    dia_chi_nguoi_nhan,
                    ghi_chu,
                    ngay_dat,
                    trang_thai_id,
                    phuong_thuc_thanh_toan_id,
                    tong_tien,
                    ma_don_hang,
                    trang_thai_thanh_toan_id
                ) 
                VALUES (
                    :tai_khoan_id,
                    :ten_nguoi_nhan,
                    :email_nguoi_nhan,
                    :sdt_nguoi_nhan,
                    :dia_chi_nguoi_nhan,
                    :ghi_chu,
                    :ngay_dat,
                    :trang_thai_id,
                    :phuong_thuc_thanh_toan_id,
                    :tong_tien,
                    :ma_don_hang,
                    :trang_thai_thanh_toan_id
                )';

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([
                'tai_khoan_id' => $tai_khoan_id,
                'ten_nguoi_nhan' => $ten_nguoi_nhan,
                'email_nguoi_nhan' => $email_nguoi_nhan,
                'sdt_nguoi_nhan' => $sdt_nguoi_nhan,
                'dia_chi_nguoi_nhan' => $dia_chi_nguoi_nhan,
                'ghi_chu' => $ghi_chu,
                'ngay_dat' => $ngay_dat,
                'trang_thai_id' => $trang_thai_id,
                'phuong_thuc_thanh_toan_id' => $phuong_thuc_thanh_toan_id,
                'tong_tien' => $tong_tien,
                'ma_don_hang' => $ma_don_hang,
                'trang_thai_thanh_toan_id' => $trang_thai_thanh_toan_id
            ]);

            return $this->conn->lastInsertId();
        } catch (Exception $e) {
            echo 'Lỗi: ' . $e->getMessage();
        }
    }

    public function addChiTietDonHang(
        $don_hang_id,
        $san_pham_id,
        $don_gia,
        $so_luong,
        $thanh_tien
    ) {
        try {
            $sql = "INSERT INTO chi_tiet_don_hangs (
                don_hang_id,
                san_pham_id,
                don_gia,
                so_luong,
                thanh_tien
            )
            VALUES 
            (
                :don_hang_id,
                :san_pham_id,
                :don_gia,
                :so_luong,
                :thanh_tien
            )
            ";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                'don_hang_id' => $don_hang_id,
                'san_pham_id' => $san_pham_id,
                'don_gia' => $don_gia,
                'so_luong' => $so_luong,
                'thanh_tien' => $thanh_tien
            ]);

            return true;
        } catch (Exception $e) {
            echo 'Lỗi: ' . $e->getMessage();
        }
    }

    // Hàm xóa bớt số lượng sản phẩm trong kho
    public function xoaSanPhamTrongKho($san_pham_id, $so_luong)
    {
        try {
            // Cập nhật số lượng sản phẩm trong kho
            $sql = "UPDATE san_phams 
                    SET so_luong = so_luong - :so_luong 
                    WHERE id = :san_pham_id AND so_luong >= :so_luong";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                'san_pham_id' => $san_pham_id,
                'so_luong' => $so_luong
            ]);

            // Kiểm tra nếu số lượng còn lại không đủ để xóa thì throw exception
            if ($stmt->rowCount() == 0) {
                throw new Exception('Số lượng sản phẩm không đủ để giảm.');
            }

            return true;
        } catch (Exception $e) {
            echo 'Lỗi: ' . $e->getMessage();
        }
    }

    public function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            )
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return $result;
    }

    public function getDonHangByMa($ma_don_hang)
    {
        $sql = "SELECT * FROM don_hangs WHERE ma_don_hang = :ma_don_hang";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':ma_don_hang', $ma_don_hang);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // Lấy kết quả của đơn hàng
    }

    public function capNhatTrangThaiThanhToan($ma_don_hang, $trang_thai_thanh_toan_id)
    {
        try {
            $sql = "UPDATE don_hangs SET trang_thai_thanh_toan_id = :trang_thai_thanh_toan_id WHERE ma_don_hang = :ma_don_hang";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':ma_don_hang', $ma_don_hang);
            $stmt->bindParam(':trang_thai_thanh_toan_id', $trang_thai_thanh_toan_id);
            return $stmt->execute(); // Thực thi câu lệnh SQL và trả về kết quả (true/false)
        } catch (Exception $e) {
            echo 'Lỗi: ' . $e->getMessage();
        }
    }
}
