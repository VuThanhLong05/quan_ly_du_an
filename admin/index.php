<?php

session_start();

// Require file Common
require_once '../commons/env.php'; // Correct path to env.php
require_once '../commons/function.php'; // Correct path to function.php

// Require toàn bộ file Controllers
require_once './controllers/AdminDanhMucController.php';
require_once './controllers/AdminSanPhamController.php';

// Require toàn bộ file Models
require_once './models/AdminDanhMuc.php';
require_once './models/AdminSanPham.php';

// Route
$act = $_GET['act'] ?? '/';

match ($act) {
    // '/' => (new AdminThongKeController())->home(),

    //danh mục

    'danh-muc' => (new AdminDanhMucController())->danhSachDanhMuc(),
    'form-them-danh-muc' => (new AdminDanhMucController())->formAddDanhMuc(),
    'them-danh-muc' => (new AdminDanhMucController())->postAddDanhMuc(),
    'xoa-danh-muc' => (new AdminDanhMucController())->deleteDanhMuc(),
    'sua-danh-muc' => (new AdminDanhMucController())->postEditAddDanhMuc(),
    'form-sua-danh-muc' => (new AdminDanhMucController())->formEditAddDanhMuc(),

    // rou sản phẩm

    'san-pham' => (new AdminSanPhamController())->danhSachSanPham(),
    'form-sua-san-pham' => (new AdminSanPhamController())->formEditAddSanPham(),
    'sua-san-pham' => (new AdminSanPhamController())->postEditAddSanPham(),
    'sua-album-san-pham' => (new AdminSanPhamController())->postEditAnhSanPham(),
    'xoa-san-pham' => (new AdminSanPhamController())->deleteSanPham(),
    'them-san-pham' => (new AdminSanPhamController())->postAddSanPham(),
    'form-them-san-pham' => (new AdminSanPhamController())->formAddSanPham(),
    'chi-tiet-san-pham' => (new AdminSanPhamController())->getDetailSanPham(),
};
