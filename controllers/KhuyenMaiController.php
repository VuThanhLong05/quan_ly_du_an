<?php
class KhuyenMaiController
{
    private $modelKhuyenMai;
    public $modelSanPham;

    public function __construct()
    {
        $this->modelKhuyenMai = new KhuyenMai();
        $this->modelSanPham = new SanPham();
    }

    public function danhSachKhuyenMai()
    {

        $listKhuyenMai = $this->modelKhuyenMai->getAllKhuyenMai();
        $listDanhMuc = $this->modelSanPham->getAllDanhMuc();

        require_once './views/khuyenmai/listKhuyenMai.php';
    }

    public function applyCoupon()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $coupon_code = trim($_POST['coupon_code']);  // Lấy mã khuyến mãi từ người dùng

            // Kiểm tra xem người dùng có nhập mã khuyến mãi không
            if (empty($coupon_code)) {
                $this->redirectWithMessage('Vui lòng nhập mã khuyến mãi!', BASE_URL . "?act=thanh-toan");
                return;
            }

            // Kiểm tra mã khuyến mãi hợp lệ
            $coupon = $this->modelKhuyenMai->validateCoupon($coupon_code);

            // Nếu mã hợp lệ
            if ($coupon) {
                $_SESSION['discount'] = $coupon['gia_tri'];  // Lưu giá trị giảm giá vào session
                $_SESSION['coupon_code'] = $coupon_code;    // Lưu mã khuyến mãi vào session
                $this->redirectWithMessage("Áp dụng mã: $coupon_code thành công! Giảm " . formatprice($coupon['gia_tri']) . " VNĐ", BASE_URL . "?act=thanh-toan");
                // xoaKhuyenMai();
            } else {
                // Nếu mã không hợp lệ hoặc đã hết hạn
                $this->redirectWithMessage('Mã khuyến mãi không hợp lệ hoặc đã hết hạn!', BASE_URL . "?act=thanh-toan");
            }
        }
    }




    // Redirect with a message
    private function redirectWithMessage($message, $url)
    {
        echo "<script>alert('$message'); window.location.href='$url';</script>";
    }
}
