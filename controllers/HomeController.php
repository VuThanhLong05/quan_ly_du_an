<?php

class HomeController
{
    public $modelSanPham;
    public $modelTaiKhoan;
    public $modelGioHang;
    public $modelDonHang;
    public $modelTinTuc;

    public function __construct()
    {
        $this->modelSanPham = new SanPham();
        $this->modelTaiKhoan = new TaiKhoan();
        $this->modelGioHang = new GioHang();
        $this->modelDonHang = new DonHang();
        $this->modelTinTuc = new TinTuc();
    }

    public function home()
    {
        // Lấy danh sách sản phẩm từ cơ sở dữ liệu
        $listSanPham = $this->modelSanPham->getAllSanPham();
        $listDanhMuc = $this->modelSanPham->getAllDanhMuc();
        $news = $this->modelTinTuc->getAllTinTuc();

        // $userId = $_SESSION['user_client']['id'];
        // $gioHang = $this->modelGioHang->getGioHangFromUser($userId);
        // // Nếu giỏ hàng chưa tồn tại, tạo mới
        // if (!$gioHang) {
        //     $gioHangId = $this->modelGioHang->addGioHang($userId);
        //     $gioHang = ['id' => $gioHangId];
        // }
        // $chiTietGioHang = $this->modelGioHang->getDetailGioHang($gioHang['id']);
        require_once './views/home.php';
    }

    public function danhSachSanPham()
    {

        $listSanPham = $this->modelSanPham->getAllSanPham();
        $listDanhMuc = $this->modelSanPham->getAllDanhMuc();
        require_once './views/sanpham/listSanPham.php';
    }
    public function chiTietSanPham()
    {
        $id = $_GET['id_san_pham'];
        $thongTinSanPham = $this->modelSanPham->getDetailSanPham($id);
        $listAnhSanPham = $this->modelSanPham->getListAnhSanPham($id);
        $listBinhLuan = $this->modelSanPham->getBinhLuanFromSanPham($id);
        $listDanhGia = $this->modelSanPham->getDanhGiaFromSanPham($id) ?? [];
        $listSanPhamCungDanhMuc = $this->modelSanPham->getListSanPhamDanhMuc($thongTinSanPham['danh_muc_id']);
        $listDanhMuc = $this->modelSanPham->getAllDanhMuc();
        if ($thongTinSanPham) {
            require_once './views/sanpham/detailSanPham.php';
        } else {
            header('location: ' . BASE_URL);
            exit();
        }
    }

    public function timKiemSanPham()
    {
        // Lấy các tham số từ GET
        $danhMuc = $_GET['danh_muc'] ?? 'all';
        $mucGia = $_GET['muc_gia'] ?? 'all';
        $sapXep = $_GET['sap_xep'] ?? 'asc';

        // Gọi model để lấy danh sách sản phẩm
        $listSanPham = $this->modelSanPham->getProductInSearch(null, $danhMuc, $mucGia, $sapXep);

        // Lấy danh sách danh mục để hiển thị lại form
        $listDanhMuc = $this->modelSanPham->getAllDanhMuc();

        // Render giao diện với kết quả tìm kiếm
        require_once './views/sanpham/listSanPham.php';
    }



    // Form đăng nhập
    public function formLogin()
    {
        $listDanhMuc = $this->modelSanPham->getAllDanhMuc();
        require_once './views/auth/formLogin.php';
        deleteSessionError();
        exit();
    }

    // Xử lý đăng nhập
    public function postLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Kiểm tra thông tin đăng nhập
            $user = $this->modelTaiKhoan->checkLogin($email, $password);

            if (is_array($user)) {
                // Kiểm tra mật khẩu
                if (password_verify($password, $user['mat_khau'])) {
                    $_SESSION['user_client'] = $user;

                    if ($user['vai_tro'] == 1) {
                        $_SESSION['user_admin'] = $user;
                        header('Location:' . BASE_URL_ADMIN);
                        exit();
                    } else {
                        header('Location:' . BASE_URL);
                        exit();
                    }
                } else {
                    $_SESSION['error'] = 'Mật khẩu không chính xác';
                    $_SESSION['flash'] = true;
                    header('Location:' . BASE_URL . '?act=login');
                    exit();
                }
            } else {
                $_SESSION['error'] = 'Email không tồn tại';
                $_SESSION['flash'] = true;
                header('Location:' . BASE_URL . '?act=login');
                exit();
            }
        }
    }

    // Form đăng ký
    public function formThemTaiKhoan()
    {
        require_once './views/auth/formDangKy.php';
    }

    // Xử lý đăng ký tài khoản
    // public function postThemTaiKhoan()
    // {
    //     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //         $email = $_POST['email'];
    //         $mat_khau = $_POST['mat_khau'];
    //         $trang_thai = $_POST['trang_thai'];

    //         // Mã hóa mật khẩu
    //         $hashedPassword = password_hash($mat_khau, PASSWORD_BCRYPT);

    //         // Thêm tài khoản vào cơ sở dữ liệu
    //         $this->modelTaiKhoan->insertTaiKhoan($email, $hashedPassword, $trang_thai);

    //         // Chuyển hướng về danh sách tài khoản
    //         header('Location: ' . BASE_URL . '?act=danh-sach-tai-khoan');
    //         exit();
    //     }
    // }

    // Đăng xuất
    public function Logout()
    {
        if (isset($_SESSION['user_client']) || isset($_SESSION['user_admin'])) {
            session_destroy();
            echo "<script>
                    alert('Đăng xuất thành công');
                    window.location.href = '" . BASE_URL . "';
                  </script>";
            exit();
        } else {
            header('Location: ' . BASE_URL . '?act=login');
            exit();
        }
    }
}
