<?php
class BinhLuanController
{
    public $modelBinhLuan;

    public function __construct()
    {
        $this->modelBinhLuan = new BinhLuan();
    }

    // Chỉ giữ lại phần thêm bình luận
    public function addBinhLuan()
    {
        if (!isset($_SESSION['user_client'])) {
            echo "<script>alert('Bạn cần đăng nhập để bình luận!');</script>";
            echo "<script>window.location.href = '" . BASE_URL . "?act=login';</script>";
            return;
        }

        // Lấy thông tin bình luận
        $san_pham_id = $_POST['san_pham_id'];
        $tai_khoan_id = $_SESSION['user_client']['id'];
        $noi_dung = trim($_POST['noi_dung']);

        if (empty($noi_dung)) {
            echo "<script>alert('Nội dung bình luận không được để trống!');</script>";
            return;
        }

        // Thêm bình luận vào cơ sở dữ liệu
        $this->modelBinhLuan->addBinhLuan(
            $san_pham_id,
            $tai_khoan_id,
            $noi_dung
        );

        // Thông báo và điều hướng lại trang chi tiết sản phẩm
        echo "<script>alert('Bình luận thành công');</script>";
        echo "<script>window.location.href = '" . BASE_URL . "?act=chi-tiet-san-pham&id_san_pham=$san_pham_id';</script>";
    }
}
