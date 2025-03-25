<?php
class DonHangController
{
    private $model;
    public $modelSanPham;

    public function __construct()
    {
        $this->model = new DonHang(); // Khởi tạo model DonHang
        $this->modelSanPham = new SanPham();
    }

    public function donHang()
    {
        // Kiểm tra nếu người dùng chưa đăng nhập
        if (empty($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '?act=login');
            exit();
        }

        $tai_khoan_id = $_SESSION['user']['id'];
        $donhang = $this->model->getDonHangByUserId($tai_khoan_id); // Lấy danh sách đơn hàng
        $listDanhMuc = $this->modelSanPham->getAllDanhMuc();

        require_once './views/donhang/listDonHang.php';
    }

    public function chiTietDonHang()
    {
        // Kiểm tra nếu người dùng chưa đăng nhập
        if (empty($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '?act=login');
            exit();
        }


        $tai_khoan_id = $_SESSION['user']['id'];
        $donhang = $this->model->getDonHangByUserId($tai_khoan_id); // Lấy danh sách đơn hàng
        $listDanhMuc = $this->modelSanPham->getAllDanhMuc();
        // Lấy ID đơn hàng từ URL
        $don_hang_id = $_GET['id'];

        // Lấy chi tiết đơn hàng
        $chitietdonhang = $this->model->getChiTietDonHang($don_hang_id);

        // Hiển thị chi tiết đơn hàng
        require_once './views/donhang/detailDonHang.php';
    }
    public function huyDonHang()
    {
        // Kiểm tra nếu người dùng chưa đăng nhập
        if (empty($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '?act=login');
            exit();
        }

        // Lấy ID đơn hàng từ URL và tài khoản người dùng
        $don_hang_id = $_GET['id'];
        $tai_khoan_id = $_SESSION['user']['id'];

        // Gọi model để hủy đơn hàng
        $result = $this->model->huyDonHang($don_hang_id, $tai_khoan_id);

        if ($result) {
            $_SESSION['message'] = 'Hủy đơn hàng thành công.';
        } else {
            $_SESSION['message'] = 'Hủy đơn hàng thất bại. Vui lòng thử lại.';
        }

        // Chuyển hướng về trang danh sách đơn hàng
        header('Location: ' . BASE_URL . '?act=don-hang');
        exit();
    }
    public function daNhanDonHang()
    {
        // Kiểm tra nếu người dùng chưa đăng nhập
        if (empty($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '?act=login');
            exit();
        }

        // Lấy ID đơn hàng từ URL và tài khoản người dùng
        $don_hang_id = $_GET['id'];
        $tai_khoan_id = $_SESSION['user']['id'];

        // Gọi model để cập nhật trạng thái đơn hàng
        $result = $this->model->daNhanDonHang($don_hang_id, $tai_khoan_id);

        if ($result) {
            $_SESSION['message'] = 'Đã xác nhận nhận hàng thành công.';
        } else {
            $_SESSION['message'] = 'Không thể xác nhận nhận hàng. Vui lòng thử lại.';
        }

        // Chuyển hướng về trang danh sách đơn hàng
        header('Location: ' . BASE_URL . '?act=don-hang');
        exit();
    }
}
