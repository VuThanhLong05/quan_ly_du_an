<?php
class AdminSanPhamController
{
    public $modelSanPham;
    public $modelDanhMuc;

    public function __construct()
    {
        $this->modelSanPham = new AdminSanPham();
        $this->modelDanhMuc = new AdminDanhMuc();
    }

    public function danhSachSanPham()
    {

        $listSanPham = $this->modelSanPham->getAllSanPham();

        require_once './views/sanpham/listSanPham.php';
    }

    public function formEditAddSanPham()
    {


        $id = $_GET['id_san_pham'];
        $sanPham =  $this->modelSanPham->getDetailSanPham($id);
        // var_dump($sanPham); die();
        $listAnhSanPham = $this->modelSanPham->getListAnhSanPham($id);
        $listDanhMuc = $this->modelDanhMuc->getAllDanhMuc();
        // var_dump($danhMuc); die();
        if ($sanPham) {
            require_once './views/sanpham/editSanPham.php';
            deleteSessionError();
        } else {
            header('location: ' . BASE_URL_ADMIN . '?act=san-pham');
            exit();
        }

        // Lấy ra thông tin của danh mục cần sửa
        // var_dump('Form thêm');
    }

    // Sửa sản phẩm
    public function postEditAddSanPham()
    {

        // Kiểm tra xem dữ liệu có submit lên không
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // var_dump('abc'); die();
            // Lấy ra dữ liệu cũ ở sản phẩm
            $san_pham_id = $_POST['san_pham_id'] ?? '';
            // Truy vấn
            $sanPhamOld = $this->modelSanPham->getDetailSanPham($san_pham_id);
            $old_file = $sanPhamOld['hinh_anh'];

            // code
            // Lấy ra dữ liệu
            // var_dump($_FILES); die();
            $ten_san_pham = $_POST['ten_san_pham'] ?? '';
            $gia_san_pham = $_POST['gia_san_pham'] ?? '';
            $gia_khuyen_mai = $_POST['gia_khuyen_mai'] ?? '';
            $so_luong = $_POST['so_luong'] ?? '';
            $ngay_nhap = $_POST['ngay_nhap'] ?? '';
            $danh_muc_id = $_POST['danh_muc_id'] ?? '';
            $trang_thai = $_POST['trang_thai'] ?? '';
            $mo_ta = $_POST['mo_ta'] ?? '';

            $hinh_anh = $_FILES['hinh_anh']  ?? null;

            // Tạo 1 mảng trống để chứa dữ liệu
            $errors = [];
            if (empty($ten_san_pham)) {
                $errors['ten_san_pham'] = 'Tên sản phẩm không được để trống';
            }

            if (empty($gia_san_pham)) {
                $errors['gia_san_pham'] = 'Giá sản phẩm không được để trống';
            }

            // if (empty($gia_khuyen_mai)) {
            //     $errors['gia_khuyen_mai'] = 'Giá khuyến không được để trống';
            // }

            if (empty($so_luong)) {

                $errors['so_luong'] = 'Số lượng được để trống';
            }

            if (empty($ngay_nhap)) {
                $errors['ngay_nhap'] = 'Ngày nhập không được để trống';
            }

            if (empty($danh_muc_id)) {
                $errors['danh_muc_id'] = 'Phải chọn danh mục sản phẩm';
            }

            if (empty($trang_thai)) {
                $errors['trang_thai'] = 'Phải chọn trạng thái sản phẩm';
            }


            $_SESSION['error'] = $errors;
            // var_dump($errors);die();
            // logic sửa ảnh
            if (isset($hinh_anh) && $hinh_anh['error'] == UPLOAD_ERR_OK) {
                // upload ảnh mới lên
                $new_file = uploadFile($hinh_anh, './uploads/');
                if (!empty($old_file)) {
                    deleteFile($old_file);
                }
            } else {
                $new_file = $old_file;
            }

            // Nếu không có lỗi tiến hành thêm sản phẩm
            if (empty($errors)) {

                // Nếu không có lỗi thì tiến hành thêm sản phẩm
                // var_dump('ok'); die();

                $this->modelSanPham->updateSanPham(
                    $san_pham_id,
                    $ten_san_pham,
                    $gia_san_pham,
                    $gia_khuyen_mai,
                    $so_luong,
                    $ngay_nhap,
                    $danh_muc_id,
                    $trang_thai,
                    $mo_ta,
                    $new_file
                );

                // var_dump($status);
                // die();

                header('location: ' . BASE_URL_ADMIN . '?act=san-pham');
                exit();
            } else {
                // Trả về form và lỗi
                // Đặt chỉ thị xóa se
                $_SESSION['flash'] = true;

                header('location: ' . BASE_URL_ADMIN . '?act=form-sua-san-pham&id_san_pham=' . $san_pham_id);
                exit();
            }
        }
    }


    public function postEditAnhSanPham()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $san_pham_id = $_POST['san_pham_id'] ?? '';
            // lấy danh sách ảnh hiện tại của sản phẩm
            $listAnhSanPhamCurrent = $this->modelSanPham->getListAnhSanPham($san_pham_id);
            // Xử lý ảnh đc gửi từ form
            $img_array = $_FILES['img_array'];
            $img_delete = isset($_POST['img_delete']) ? explode(',', $_POST['img_delete']) : [];
            $current_img_ids = $_POST['current_img_ids'] ?? [];

            $upload_file = [];
            foreach ($img_array['name'] as $key => $value) {
                if ($img_array['error'][$key] == UPLOAD_ERR_OK) {
                    $new_file = uploadFileAlbum($img_array, './uploads/', $key);
                    if ($new_file) {
                        $upload_file[] = [
                            'id' => $current_img_ids[$key] ?? null,
                            'file' => $new_file
                        ];
                    }
                }
            }

            foreach ($upload_file as $file_info) {
                if ($file_info['id']) {
                    $old_file = $this->modelSanPham->getDetailAnhSanPham($file_info['id'])['link_hinh_anh'];
                    // cập nhật ảnh cũ
                    $this->modelSanPham->updateAnhSanPham($file_info['id'], $file_info['file']);
                    // xóa ảnh cũ
                    deleteFile($old_file);
                } else {
                    $this->modelSanPham->insertAlbumAnhSanPham($san_pham_id, $file_info['file']);
                }
            }

            // xử lý xóa ảnh
            foreach ($listAnhSanPhamCurrent as $anhSP) {
                $anh_id = $anhSP['id'];
                if (in_array($anh_id, $img_delete)) {
                    $this->modelSanPham->destroyAnhSanPham($anh_id);
                    deleteFile($anhSP['link_hinh_anh']);
                }
            }
            header("Location:" . BASE_URL_ADMIN . '?act=form-sua-san-pham&id_san_pham=' . $san_pham_id);
            exit();
        }
    }

    public function deleteSanPham()
    {
        // Lấy ra id danh mục cần xóa
        $id = $_GET['id_san_pham'];
        $sanPham =  $this->modelSanPham->getDetailSanPham($id);

        $listAnhSanPham = $this->modelSanPham->getListAnhSanPham($id);
        if ($listAnhSanPham) {
            foreach ($listAnhSanPham as $key => $anhSP) {
                deleteFile($anhSP['link_hinh_amh']);
                $this->modelSanPham->destroyAnhSanPham($anhSP['id']);
            }
        }

        if ($sanPham) {
            // Xóa sản phẩm
            deleteFile($sanPham['hinh_anh']);

            $this->modelSanPham->destroySanPham($id);
        }
        header('location: ' . BASE_URL_ADMIN . '?act=san-pham');
        exit();
    }
    public function updateTrangThaiBinhLuan()
    {
        $id_binh_luan = $_POST['id_binh_luan'];
        $name_view = $_POST['name_view'];
        $binhLuan = $this->modelSanPham->getDetailBinhLuan($id_binh_luan);

        if ($binhLuan) {
            $trang_thai_update = '';
            if ($binhLuan['trang_thai'] == 1) {
                $trang_thai_update = 2;
            } else {
                $trang_thai_update = 1;
            }
            $status = $this->modelSanPham->updateTrangThaiBinhLuan($id_binh_luan, $trang_thai_update);
            if ($status) {
                if ($name_view == 'detail_khach') {
                    header('location: ' . BASE_URL_ADMIN . '?act=chi-tiet-khach-hang&id_khach_hang=' . $binhLuan['tai_khoan_id']);
                    exit();
                } else {
                    header('location: ' . BASE_URL_ADMIN . '?act=chi-tiet-san-pham&id_san_pham=' . $binhLuan['san_pham_id']);
                    exit();
                }
            }
        }
    }
}
