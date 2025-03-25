<?php
session_start();
// Require file Common
require_once './commons/env.php'; // Khai báo biến môi trường
require_once './commons/function.php'; // Hàm hỗ trợ

// Require toàn bộ file Controllers
// require_once './controllers/TaiKhoanController.php';
require_once './controllers/HomeController.php';
require_once './controllers/TaiKhoanController.php';
require_once './controllers/LienHeController.php';
require_once './controllers/KhuyenMaiController.php';
require_once './controllers/BinhLuanController.php';
require_once './controllers/TinTucController.php';
require_once './controllers/DonHangController.php';
require_once './controllers/GioHangController.php';
require_once './controllers/ThanhToanController.php';
require_once './controllers/ThanhToanMomoController.php';

// require_once './views/sanpham/search.php';


// Require toàn bộ file Models
require_once './models/SanPham.php';
require_once './models/TaiKhoan.php';
require_once './models/GioHang.php';
require_once './models/LienHe.php';
require_once './models/KhuyenMai.php';
require_once './models/BinhLuan.php';
require_once './models/TinTuc.php';
require_once './models/DonHang.php';
require_once './models/GioHang.php';
require_once './models/ThanhToanMomo.php';
// require_once './models/Coupon.php';


// Route
$act = $_GET['act'] ?? '/';
// var_dump($_GET['act']);die();

// Để bảo bảo tính chất chỉ gọi 1 hàm Controller để xử lý request thì mình sử dụng match

match ($act) {
    // Trang chủ
    '/' => (new HomeController())->home(),

    // sản phẩm
    'chi-tiet-san-pham' => (new HomeController())->chiTietSanPham(),
    'danh-sach-san-pham' => (new HomeController())->danhSachSanPham(),


    // đăng ký nhập
    'login' => (new HomeController())->formLogin(),
    'check-login' => (new HomeController())->postLogin(),
    'logout' => (new HomeController())->Logout(),

    // tài khoản
    'list-tai-khoan' => (new TaiKhoanController())->danhSach(),
    'form-them' => (new TaiKhoanController())->formAdd(),
    'them' => (new TaiKhoanController())->postAdd(),
    /// quản lý tài khoản cá nhân 
    'detail-tai-khoan' => (new TaiKhoanController())->detailTaiKhoan(),
    'update-tai-khoan' => (new TaiKhoanController())->updateTaiKhoan(),
    // 'sua-mat-khau-ca-nhan-quan-tri' => (new TaiKhoanController())->postEditMatKhauCaNhan(),

    // Quản lý liên hệ
    // 'lien-he' => (new LienHeController())->danhSach(),
    'form-them-lien-he' => (new LienHeController())->formAdd(), // Hiển thị form thêm liên hệ
    'them-lien-he' => (new LienHeController())->postAdd(),      // Xử lý thêm liên hệ

    // Khuyến mãi
    'danh-sach-khuyen-mai' => (new KhuyenMaiController())->danhSachKhuyenMai(),


    // Bình luận
    'form-them-binh-luan' => (new BinhLuanController())->addBinhLuan(),

    /// quanr lí tin tức 
    'danh-sach-tin-tuc' => (new TinTucController())->danhSachTinTuc(),
    'chi-tiet-tin-tuc' => (new TinTucController())->detailTinTuc(),

    // search
    'tim-kiem' => (new HomeController())->timKiemSanPham(),

    // đơn hàng
    'don-hang' => (new DonHangController())->donHang(),
    'chi-tiet-don-hang' => (new DonHangController())->chiTietDonHang(),
    'huy-don-hang' => (new DonHangController())->huyDonHang(),
    'da-nhan-don-hang' => (new DonHangController())->daNhanDonHang(),

    // Giỏ hàng
    'them-gio-hang' => (new GioHangController())->addGioHang(),
    'gio-hang' => (new GioHangController())->gioHang(),
    'xoa-san-pham' => (new GioHangController())->xoaSanPham(),
    'lam-moi-gio-hang' => (new GioHangController())->xoaGioHang(),
    'cap-nhat-gio-hang' => (new GioHangController())->capNhatGioHang(),

    // Thanh toán
    'thanh-toan' => (new ThanhToanController())->thanhToan(),
    'xu-ly-thanh-toan' => (new ThanhToanController())->postThanhToan(),
    // 'thanh-toan-momo' => (new ThanhToanController())->thanhToanMomo(),
    'thanh-toan-thanh-cong' => (new ThanhToanController())->thanhToanThanhCong(),
    'apply-coupon' => (new KhuyenMaiController())->applyCoupon(),
};
