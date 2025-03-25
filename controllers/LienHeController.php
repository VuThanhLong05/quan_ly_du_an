<?php
class LienHeController
{
    private $model;
    public $modelSanPham;

    public function __construct()
    {
        // Kết nối database và khởi tạo model
        $this->model = new LienHeModel();
        $this->modelSanPham = new SanPham();
    }

    /**
     * Xử lý logic thêm liên hệ từ form
     */

    public function formAdd()
    {
        $listDanhMuc = $this->modelSanPham->getAllDanhMuc();
        require_once './views/lienhe/formThemLienHe.php';
        deleteSessionError();
    }
    public function postAdd()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy dữ liệu từ form
            $email = $_POST['email'] ?? '';
            $noi_dung = $_POST['noi_dung'] ?? '';
            $ngay_tao = date('Y-m-d'); // Tự động lấy ngày hôm nay
            $trang_thai = 1; // Mặc định trạng thái là 2

            // Gọi model để thêm dữ liệu vào database
            if ($this->model->addLienHe($email, $noi_dung, $ngay_tao, $trang_thai)) {
                // Gửi thông báo khi thêm thành công
                echo "<script>
                    alert('Thêm liên hệ thành công!');
                    window.location.href = 'http://localhost/duan_1/';
                  </script>";
            } else {
                echo "<script>alert('Đã xảy ra lỗi khi thêm liên hệ.');</script>";
            }
        } else {
            echo "<script>alert('Phương thức không hợp lệ.');</script>";
        }
    }
}

// Khởi tạo controller và xử lý yêu cầu
