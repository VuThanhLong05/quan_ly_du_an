<?php

class GioHangController
{
    public $modelSanPham;
    public $modelTaiKhoan;
    public $modelGioHang;

    public function __construct()
    {
        $this->modelSanPham = new SanPham();
        $this->modelTaiKhoan = new TaiKhoan();
        $this->modelGioHang = new GioHang();
    }

    public function addGioHang()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Kiểm tra xem người dùng đã đăng nhập hay chưa
            if (isset($_SESSION['user_client'])) {
                $userId = $_SESSION['user_client']['id'];
                $gioHang = $this->modelGioHang->getGioHangFromUser($userId);

                if (!$gioHang) {
                    $gioHangId = $this->modelGioHang->addGioHang($userId);
                    $gioHang = ['id' => $gioHangId];
                    $chiTietGioHang = [];
                } else {
                    $chiTietGioHang = $this->modelGioHang->getDetailGioHang($gioHang['id']);
                }

                $san_pham_id = $_POST['san_pham_id'];
                $so_luong = $_POST['so_luong'];
                $checkSanPham = false;

                // Kiểm tra số lượng tồn kho của sản phẩm
                $soLuongTonKho = $this->modelGioHang->getSoLuongTonKho($san_pham_id);
                if ($soLuongTonKho < $so_luong) {
                    echo "<script>alert('Sản phẩm không đủ hàng trong kho. Vui lòng chọn số lượng nhỏ hơn!');</script>";
                    echo "<script>window.history.back();</script>";
                    die;
                }

                foreach ($chiTietGioHang as $detail) {
                    if ($detail['san_pham_id'] == $san_pham_id) {
                        $newSoLuong = $detail['so_luong'] + $so_luong;

                        // Kiểm tra nếu số lượng sau khi cộng vượt quá tồn kho
                        if ($newSoLuong > $soLuongTonKho) {
                            echo "<script>alert('Sản phẩm không đủ hàng trong kho. Vui lòng chọn số lượng nhỏ hơn!');</script>";
                            echo "<script>window.history.back();</script>";
                            die;
                        }

                        $this->modelGioHang->updateSoLuong($gioHang['id'], $san_pham_id, $newSoLuong);
                        $checkSanPham = true;
                        break;
                    }
                }

                if (!$checkSanPham) {
                    $this->modelGioHang->addDetailGioHang($gioHang['id'], $san_pham_id, $so_luong);
                }

                header('location:' . BASE_URL . '?act=gio-hang');
                die;
            } else {
                echo "<script>alert('Bạn cần đăng nhập để thêm vào giỏ hàng!');</script>";
                echo "<script>window.location.href = '" . BASE_URL . "?act=login';</script>";
                die;
            }
        }
    }


    public function gioHang()
    {
        // Kiểm tra người dùng đã đăng nhập chưa
        if (isset($_SESSION['user_client'])) {
            // Lấy ID người dùng từ session
            $userId = $_SESSION['user_client']['id'];


            $listDanhMuc = $this->modelSanPham->getAllDanhMuc();
            // Lấy thông tin giỏ hàng của người dùng
            $gioHang = $this->modelGioHang->getGioHangFromUser($userId);

            // Nếu giỏ hàng chưa tồn tại, tạo mới
            if (!$gioHang) {
                $gioHangId = $this->modelGioHang->addGioHang($userId);
                $gioHang = ['id' => $gioHangId];
            }

            // Lấy chi tiết giỏ hàng
            if (!$gioHang) {
                $chiTietGioHang = [];
            } else {
                // Lấy chi tiết giỏ hàng nếu giỏ hàng đã tồn tại
                $chiTietGioHang = $this->modelGioHang->getDetailGioHang($gioHang['id']);
            }
            $chiTietGioHang = $this->modelGioHang->getDetailGioHang($gioHang['id']);

            // Hiển thị giao diện giỏ hàng
            require_once './views/gioHang.php';
        } else {
            // Người dùng chưa đăng nhập
            echo "<script>alert('Bạn cần đăng nhập để xem giỏ hàng!');</script>";
            echo "<script>window.location.href = '" . BASE_URL . "?act=login';</script>";
            die;
        }
    }

    public function xoaGioHang()
    {
        // Kiểm tra xem người dùng đã đăng nhập chưa
        if (isset($_SESSION['user_client'])) {
            // Lấy ID người dùng từ session
            $userId = $_SESSION['user_client']['id'];
            $user = $_SESSION['user_client']['id'];
            // var_dump($user);
            // die();
            $tai_khoan_id = $user;

            // Lấy giỏ hàng của người dùng dựa trên ID
            $gioHang = $this->modelGioHang->getGioHangFromUser($userId);

            // Kiểm tra xem giỏ hàng có tồn tại không
            if ($gioHang) {
                try {
                    // Xóa chi tiết giỏ hàng
                    $this->modelGioHang->xoaTatCaChiTietGioHang($gioHang['id']);

                    // Xóa giỏ hàng
                    $this->modelGioHang->xoaGioHang($tai_khoan_id);

                    // Thông báo và chuyển hướng người dùng
                    // echo "<script>alert('Làm mới giỏ hàng thành công!');</script>";
                    echo "<script>window.location.href = '" . BASE_URL . "?act=gio-hang';</script>";
                    die;
                } catch (Exception $e) {
                    // Nếu có lỗi khi xóa, thông báo lỗi
                    echo "<script>alert('Có lỗi khi xóa giỏ hàng: " . $e->getMessage() . "');</script>";
                    echo "<script>window.location.href = '" . BASE_URL . "?act=gio-hang';</script>";
                    die;
                }
            } else {
                // Nếu không tìm thấy giỏ hàng, thông báo và chuyển hướng
                echo "<script>alert('Giỏ hàng không tồn tại!');</script>";
                echo "<script>window.location.href = '" . BASE_URL . "?act=gio-hang';</script>";
                die;
            }
        } else {
            // Nếu người dùng chưa đăng nhập
            echo "<script>alert('Bạn cần đăng nhập để xóa giỏ hàng!');</script>";
            echo "<script>window.location.href = '" . BASE_URL . "?act=login';</script>";
            die;
        }
    }


    public function xoaSanPham()
    {
        // Kiểm tra nếu người dùng đã đăng nhập
        if (isset($_SESSION['user_client'])) {
            // Lấy ID người dùng từ session
            $userId = $_SESSION['user_client']['id'];

            // Lấy ID sản phẩm cần xóa từ URL
            $sanPhamId = $_GET['id'];

            // Kiểm tra xem sản phẩm có tồn tại trong giỏ hàng của người dùng không
            $gioHang = $this->modelGioHang->getGioHangFromUser($userId);

            if ($gioHang) {
                // Gọi hàm xóa sản phẩm trong giỏ hàng
                $this->modelGioHang->xoaSanPhamTrongGioHang($gioHang['id'], $sanPhamId);

                // Thông báo và chuyển hướng về giỏ hàng
                // echo "<script>alert('Sản phẩm đã được xóa khỏi giỏ hàng!');</script>";
                echo "<script>window.location.href = '" . BASE_URL . "?act=gio-hang';</script>";
                // die;
            } else {
                // Nếu giỏ hàng không tồn tại
                echo "<script>alert('Giỏ hàng không tồn tại!');</script>";
                echo "<script>window.location.href = '" . BASE_URL . "?act=gio-hang';</script>";
                // die;
            }
        } else {
            // Nếu người dùng chưa đăng nhập
            echo "<script>alert('Bạn cần đăng nhập để xóa sản phẩm khỏi giỏ hàng!');</script>";
            echo "<script>window.location.href = '" . BASE_URL . "?act=login';</script>";
            // die;
        }
    }


    public function capNhatGioHang()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_SESSION['user_client'])) {
                $userId = $_SESSION['user_client']['id'];

                // Lấy giỏ hàng của người dùng
                $gioHang = $this->modelGioHang->getGioHangFromUser($userId);
                if (!$gioHang) {
                    echo "<script>alert('Giỏ hàng không tồn tại!');</script>";
                    echo "<script>window.location.href = '" . BASE_URL . "?act=gio-hang';</script>";
                    die;
                }

                $so_luongs = $_POST['so_luong'];

                foreach ($so_luongs as $san_pham_id => $so_luong) {
                    // Kiểm tra tồn kho
                    $ton_kho = $this->modelGioHang->getSoLuongTonKho($san_pham_id);
                    if ($so_luong > $ton_kho) {
                        echo "<script>alert('Số lượng vượt quá tồn kho!');</script>";
                        echo "<script>window.history.back();</script>";
                        die;
                    }

                    // Cập nhật số lượng
                    $this->modelGioHang->updateSoLuong($gioHang['id'], $san_pham_id, $so_luong);
                }

                echo "<script>alert('Cập nhật giỏ hàng thành công!');</script>";
                echo "<script>window.location.href = '" . BASE_URL . "?act=gio-hang';</script>";
            } else {
                echo "<script>alert('Bạn cần đăng nhập để thực hiện chức năng này!');</script>";
                echo "<script>window.location.href = '" . BASE_URL . "?act=login';</script>";
                die;
            }
        }
    }

    public function applyDiscountCode()
    {
        if (isset($_SESSION['user_client'])) {
            // Lấy ID người dùng từ session
            $userId = $_SESSION['user_client']['id'];

            // Lấy mã khuyến mãi từ form
            if (isset($_POST['discount_code'])) {
                $discountCode = $_POST['discount_code'];

                // Kiểm tra mã khuyến mãi (bạn có thể kiểm tra từ cơ sở dữ liệu hoặc danh sách mã đã định nghĩa)
                $validDiscount = $this->modelGioHang->getDiscountByCode($discountCode);

                if ($validDiscount) {
                    // Áp dụng mã giảm giá
                    $discountAmount = $validDiscount['amount']; // Số tiền giảm (theo tỷ lệ hoặc giá trị cố định)
                    $this->modelGioHang->applyDiscountToCart($userId, $discountAmount);

                    // Thông báo áp dụng thành công
                    echo "<script>alert('Khuyến mãi đã được áp dụng!');</script>";
                    echo "<script>window.location.href = '" . BASE_URL . "?act=gio-hang';</script>";
                } else {
                    // Mã khuyến mãi không hợp lệ
                    echo "<script>alert('Mã khuyến mãi không hợp lệ!');</script>";
                    echo "<script>window.location.href = '" . BASE_URL . "?act=gio-hang';</script>";
                }
            }
        } else {
            echo "<script>alert('Bạn cần đăng nhập để áp dụng mã khuyến mãi!');</script>";
            echo "<script>window.location.href = '" . BASE_URL . "?act=login';</script>";
        }
    }
}
