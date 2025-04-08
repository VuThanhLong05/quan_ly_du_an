<?php
class AdminDanhMucController
{
    public $modelDanhMuc;

    public function __construct()
    {
        $this->modelDanhMuc = new AdminDanhMuc();
    }

    public function danhSachDanhMuc()
    {

        $listDanhMuc = $this->modelDanhMuc->getAllDanhMuc();

        require_once './views/danhmuc/listDanhMuc.php';
    }

    public function formAddDanhMuc()
    {
        require_once './views/danhmuc/addDanhMuc.php';
        // var_dump('Form thêm');
    }

    public function postAddDanhMuc()
    {
        // var_dump($_POST);

        // Kiểm tra xem dữ liệu có submit lên không
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // code
            // Lấy ra dữ liệu
            $ten_danh_muc = $_POST['ten_danh_muc'];
            $mo_ta = $_POST['mo_ta'];

            // Tạo 1 mảng trống để chứa dữ liệu
            $errors = [];
            if (empty($ten_danh_muc)) {
                $errors['ten_danh_muc'] = 'Tên danh mục không được để trống';
            }

            // Nếu không có lỗi tiến hành thêm danh mục
            if (empty($errors)) {
                // Nếu không có lỗi thì tiến hành thêm danh mục
                // var_dump('ok');
                $this->modelDanhMuc->insertDanhMuc($ten_danh_muc, $mo_ta);
                header('location: ' . BASE_URL_ADMIN . '?act=danh-muc');
                exit();
            } else {
                // Trả về form và lỗi
                require_once './views/danhmuc/addDanhMuc.php';
            }
        }
    }
}
