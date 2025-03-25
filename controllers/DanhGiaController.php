<?php
class DanhGiaController
{
    public $modelDanhGia;

    public function __construct()
    {
        $this->modelDanhGia = new DanhGia();
    }

    public function danhSachDanhGia()
    {

        $listDanhGia = $this->modelDanhGia->getAllDanhGia();

        require_once './views/sanpham/listSanPham.php';
    }
}
