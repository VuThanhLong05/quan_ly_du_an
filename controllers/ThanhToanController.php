<?php

class ThanhToanController
{
    public $modelSanPham;
    public $modelTaiKhoan;
    public $modelGioHang;
    public $modelThanhToan;
    public $modelDonHang;
    public $modelKhuyenMai;

    public function __construct()
    {
        $this->modelSanPham = new SanPham();
        $this->modelTaiKhoan = new TaiKhoan();
        $this->modelGioHang = new GioHang();
        // $this->modelThanhToan = new ThanhToan();
        $this->modelDonHang = new DonHang();
        $this->modelKhuyenMai = new KhuyenMai();
    }

    public function thanhToan()
    {
        if (isset($_SESSION['user_client'])) {
            $userId = $_SESSION['user_client'];
            $gioHang = $this->modelGioHang->getGioHangFromUser($userId['id']);
            $listDanhMuc = $this->modelSanPham->getAllDanhMuc();

            // Kiểm tra nếu chưa có giỏ hàng hoặc giỏ hàng trống
            if (!$gioHang || empty($this->modelGioHang->getDetailGioHang($gioHang['id']))) {
                echo "<script>alert('Chưa có giỏ hàng để thanh toán!');</script>";
                echo "<script>window.location.href = '" . BASE_URL . "?act=gio-hang';</script>";
                die;
            }

            $chiTietGioHang = $this->modelGioHang->getDetailGioHang($gioHang['id']);
            require_once './views/thanhtoan/thanhToan.php';
        } else {
            echo "<script>alert('Bạn cần đăng nhập để xem giỏ hàng!');</script>";
            echo "<script>window.location.href = '" . BASE_URL . "?act=login';</script>";
            die;
        }
    }

    public function postThanhToan()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ten_nguoi_nhan = $_POST['ten_nguoi_nhan'];
            $email_nguoi_nhan = $_POST['email_nguoi_nhan'];
            $sdt_nguoi_nhan = $_POST['sdt_nguoi_nhan'];
            $dia_chi_nguoi_nhan = $_POST['dia_chi_nguoi_nhan'];
            $ghi_chu = $_POST['ghi_chu'];
            $tong_tien = $_POST['tong_tien'];
            $phuong_thuc_thanh_toan_id = $_POST['payUrl'];
            $ngay_dat = date('Y-m-d');
            $trang_thai_id = 1; // Trạng thái đơn hàng là đang chờ xử lý
            $user = $_SESSION['user_client']['id'];
            $tai_khoan_id = $user;
            $ma_don_hang = 'DH-' . rand(1000, 9999);

            // Xử lý khi thanh toán qua Momo (phương thức thanh toán 2)
            if ($phuong_thuc_thanh_toan_id == 2) {
                $this->thanhToanMomo($ma_don_hang, $tong_tien);
                die();  // Dừng lại sau khi redirect sang Momo
            }

            // Xác định trạng thái thanh toán
            $trang_thai_thanh_toan_id = ($phuong_thuc_thanh_toan_id == 1) ? 1 : 2; // 1: Thanh toán khi nhận hàng, 2: Momo

            // Tạo đơn hàng
            $donHang = $this->modelDonHang->addDonHang(
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
            );

            // Nếu đơn hàng đã được tạo thành công
            if ($donHang) {
                // Lấy giỏ hàng của người dùng
                $gioHang = $this->modelGioHang->getGioHangFromUser($tai_khoan_id);
                $chiTietGioHang = $this->modelGioHang->getDetailGioHang($gioHang['id']);

                // Lưu chi tiết đơn hàng và xóa sản phẩm trong kho
                foreach ($chiTietGioHang as $item) {
                    $donGia = $item['gia_khuyen_mai'] ?? $item['gia_san_pham'];
                    // Thêm chi tiết đơn hàng vào cơ sở dữ liệu
                    $this->modelDonHang->addChiTietDonHang(
                        $donHang,
                        $item['san_pham_id'],
                        $donGia,
                        $item['so_luong'],
                        $donGia * $item['so_luong']
                    );
                    // Giảm số lượng sản phẩm trong kho
                    $this->modelDonHang->xoaSanPhamTrongKho($item['san_pham_id'], $item['so_luong']);
                }

                // Xóa tất cả chi tiết giỏ hàng và giỏ hàng sau khi thanh toán
                $this->modelGioHang->xoaTatCaChiTietGioHang($gioHang['id']);
                $this->modelGioHang->xoaGioHang($tai_khoan_id);
                xoaKhuyenMai();


                // Thông báo thanh toán thành công
                echo "<script>alert('Đặt hàng thành công. Thanh toán khi nhận hàng.');</script>";
                echo "<script>window.location.href = '" . BASE_URL . "?act=don-hang';</script>";
                die;
            } else {
                // Nếu có lỗi khi thêm đơn hàng
                echo "<script>alert('Lỗi khi thêm đơn hàng');</script>";
                die();
            }
        }
    }

    public function thanhToanMomo($ma_don_hang, $tong_tien)
    {
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';

        $orderInfo = "Thanh toán đơn hàng $ma_don_hang";
        $orderId = $ma_don_hang;
        $redirectUrl = BASE_URL . "?act=thanh-toan-thanh-cong&ma_don_hang=" . $orderId; // Redirect sau khi thanh toán thành công
        $ipnUrl = BASE_URL . "?act=don-hang";
        $extraData = "";

        $requestId = time() . "";
        $requestType = "payWithATM";
        $rawHash = "accessKey=$accessKey&amount=$tong_tien&extraData=$extraData&ipnUrl=$ipnUrl&orderId=$orderId&orderInfo=$orderInfo&partnerCode=$partnerCode&redirectUrl=$redirectUrl&requestId=$requestId&requestType=$requestType";
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $data = array(
            'partnerCode' => $partnerCode,
            'requestId' => $requestId,
            'amount' => $tong_tien,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        );

        $result = $this->modelDonHang->execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true);

        // Lưu đơn hàng vào cơ sở dữ liệu ngay cả khi đang chờ thanh toán (vì Momo sẽ trả lại kết quả sau)
        $tai_khoan_id = $_SESSION['user_client']['id'];
        $ten_nguoi_nhan = $_POST['ten_nguoi_nhan'];
        $email_nguoi_nhan = $_POST['email_nguoi_nhan'];
        $sdt_nguoi_nhan = $_POST['sdt_nguoi_nhan'];
        $dia_chi_nguoi_nhan = $_POST['dia_chi_nguoi_nhan'];
        $ghi_chu = $_POST['ghi_chu'];
        $ngay_dat = date('Y-m-d');
        $trang_thai_id = 1; // Trạng thái đơn hàng: đang chờ xử lý
        $phuong_thuc_thanh_toan_id = 2; // Thanh toán Momo
        $trang_thai_thanh_toan_id = 1; // Trạng thái thanh toán: đang chờ

        // Tạo đơn hàng (không cần cập nhật chi tiết đơn hàng ở đây, chỉ tạo đơn)
        $donHang = $this->modelDonHang->addDonHang(
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
        );

        // Lấy giỏ hàng và lưu chi tiết đơn hàng
        if ($donHang) {
            // Lấy giỏ hàng của người dùng
            $gioHang = $this->modelGioHang->getGioHangFromUser($tai_khoan_id);
            $chiTietGioHang = $this->modelGioHang->getDetailGioHang($gioHang['id']);

            // Lưu chi tiết đơn hàng vào cơ sở dữ liệu
            foreach ($chiTietGioHang as $item) {
                $donGia = $item['gia_khuyen_mai'] ?? $item['gia_san_pham'];
                // Thêm chi tiết đơn hàng vào cơ sở dữ liệu
                $this->modelDonHang->addChiTietDonHang(
                    $donHang,
                    $item['san_pham_id'],
                    $donGia,
                    $item['so_luong'],
                    $donGia * $item['so_luong']
                );
                // Giảm số lượng sản phẩm trong kho
                $this->modelDonHang->xoaSanPhamTrongKho($item['san_pham_id'], $item['so_luong']);
            }

            // Xóa tất cả chi tiết giỏ hàng và giỏ hàng sau khi thanh toán
            $this->modelGioHang->xoaTatCaChiTietGioHang($gioHang['id']);
            $this->modelGioHang->xoaGioHang($tai_khoan_id);
            xoaKhuyenMai();


            // Chuyển hướng đến trang thanh toán Momo
            header('Location: ' . $jsonResult['payUrl']);
            exit();
        } else {
            echo "<script>alert('Lỗi khi tạo đơn hàng.');</script>";
            die();
        }
    }



    public function thanhToanThanhCong()
    {
        if (isset($_GET['ma_don_hang'])) {
            $ma_don_hang = $_GET['ma_don_hang'];
            $donHang = $this->modelDonHang->getDonHangByMa($ma_don_hang);

            // Nếu đơn hàng tồn tại, cập nhật trạng thái thanh toán
            if ($donHang) {
                // Cập nhật trạng thái thanh toán thành công (2: Thanh toán thành công)
                $this->modelDonHang->capNhatTrangThaiThanhToan($ma_don_hang, 2);

                // Sau khi thanh toán thành công, xóa giỏ hàng và sản phẩm trong kho
                $gioHang = $this->modelGioHang->getGioHangFromUser($donHang['tai_khoan_id']);
                $chiTietGioHang = $this->modelGioHang->getDetailGioHang($gioHang['id']);
                foreach ($chiTietGioHang as $item) {
                    $this->modelDonHang->xoaSanPhamTrongKho($item['san_pham_id'], $item['so_luong']);
                }

                // Xóa chi tiết giỏ hàng và giỏ hàng
                $this->modelGioHang->xoaTatCaChiTietGioHang($gioHang['id']);
                $this->modelGioHang->xoaGioHang($donHang['tai_khoan_id']);
                xoaKhuyenMai();

                echo "<script>alert('Thanh toán thành công! Đơn hàng của bạn sẽ được xử lý.');</script>";
                echo "<script>window.location.href = '" . BASE_URL . "?act=don-hang';</script>";
                die();
            }
        }
    }
}
