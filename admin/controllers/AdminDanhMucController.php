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
    public function formEditAddDanhMuc()
    {
        $id = $_GET['id_danh_muc'];
        $danhMuc =  $this->modelDanhMuc->getDetailDanhMuc($id);
        // var_dump($danhMuc); die();
        if ($danhMuc) {
            require_once './views/danhmuc/editDanhMuc.php';
        } else {
            header('location: ' . BASE_URL_ADMIN . '?act=danh-muc');
            exit();
        }

    }

    public function postEditAddDanhMuc()
    {
        // var_dump($_POST);

        // Kiểm tra xem dữ liệu có submit lên không
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // code
            // Lấy ra dữ liệu
            $id = $_POST['id'];
            $ten_danh_muc = $_POST['ten_danh_muc'];
            $mo_ta = $_POST['mo_ta'];

            // Tạo 1 mảng trống để chứa dữ liệu
            $errors = [];
            if (empty($ten_danh_muc)) {
                $errors['ten_danh_muc'] = 'Tên danh mục không được để trống';
            }

            // Nếu không có lỗi tiến hành sửa danh mục
            if (empty($errors)) {
                // Nếu không có lỗi thì tiến hành thêm danh mục
                // var_dump('ok');
                $this->modelDanhMuc->updateDanhMuc($id, $ten_danh_muc, $mo_ta);
                header('location: ' . BASE_URL_ADMIN . '?act=danh-muc');
                exit();
            } else {
                // Trả về form và lỗi
                $danhMuc = [
                    'id' => $id,
                    'ten_danh_muc' => $ten_danh_muc,
                    'mo_ta' => $mo_ta
                ];
                require_once './views/danhmuc/editDanhMuc.php';
            }
        }
    }

    public function deleteDanhMuc()
    {
        // Lấy ra id danh mục cần xóa
        $id = $_GET['id_danh_muc'];
        $danhMuc =  $this->modelDanhMuc->getDetailDanhMuc($id);

        if ($danhMuc) {
            // Xóa danh mục
            $this->modelDanhMuc->destroyDanhMuc($id);
        }
        header('location: ' . BASE_URL_ADMIN . '?act=danh-muc');
        exit();
    }
}
