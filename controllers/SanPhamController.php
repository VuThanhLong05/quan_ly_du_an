<?php
class SanPhamController
{
    public $modelSanPham;

    public function __construct()
    {
        $this->modelSanPham = new SanPham(); // Khởi tạo model TinTuc
    }

    public function danhSachSanPham()
    {
        $listDanhMuc = $this->modelSanPham->getAllDanhMuc();
        $listSanPham = $this->modelSanPham->getAllSanPham();
        require_once './views/sanpham/listSanPham.php';
    }


    public function chiTietSanPham()
    {

        $id = isset($_GET['id_san_pham']) && !empty(trim($_GET['id_san_pham'])) ? htmlspecialchars(trim($_GET['id_san_pham'])) : 0;

        if (!$id) {
            header('Location: ' . BASE_URL);
            exit();
        }

        $sanPham = $this->modelSanPham->getDetailSanPham($id);
        if (!$sanPham) {
            header('Location: ' . BASE_URL);
            exit();
        }

        $listAnhSanPham = $this->modelSanPham->getListAnhSanPham($id) ?? [];
        $listBinhLuan = $this->modelSanPham->getBinhLuanFromSanPham($id) ?? [];
        // print_r($listBinhLuan);
        // print_r($listDanhGia);
        // var_dump($listDanhGia);
        // die();
        $listSanPhamCungDanhMuc = $this->modelSanPham->getListSanPhamDanhMuc($sanPham['danh_muc_id']) ?? [];
        $listDanhMuc = $this->modelSanPham->getAllDanhMuc();

        require_once './views/sanpham/detailSanPham.php';
    }

    public function searchSP()
    {
        require_once './views/sanpham/search.php';
    }
}
