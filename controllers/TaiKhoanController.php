<?php

class TaiKhoanController
{
    public $modelTaiKhoan;
    public $modelSanPham;

    public function __construct()
    {
        $this->modelTaiKhoan = new TaiKhoan();
        $this->modelSanPham = new SanPham();
    }

    public function danhSach()
    {
        $listQuanTri = $this->modelTaiKhoan->getAllTaiKhoan(1);
        // $listDanhMuc = $this->modelSanPham->getAllDanhMuc();
    }

    public function formAdd()
    {
        $listDanhMuc = $this->modelSanPham->getAllDanhMuc();
        require_once './views/auth/formDangKy.php';
        deleteSessionError();
    }

    public function postAdd()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ho_ten = $_POST['ho_ten'];
            $email = $_POST['email'];
            $so_dien_thoai = $_POST['so_dien_thoai'];
            $mat_khau = $_POST['mat_khau'];

            $errors = [];

            // Kiểm tra email tồn tại
            if ($this->modelTaiKhoan->isEmailExists($email)) {
                $errors['email'] = 'Email đã tồn tại, vui lòng chọn email khác.';
            }

            // Kiểm tra số điện thoại tồn tại
            if ($this->modelTaiKhoan->isPhoneExists($so_dien_thoai)) {
                $errors['so_dien_thoai'] = 'Số điện thoại đã tồn tại, vui lòng chọn số khác.';
            }

            // Các kiểm tra khác
            if (empty($ho_ten)) {
                $errors['ho_ten'] = 'Tên không được để trống';
            }
            if (empty($mat_khau)) {
                $errors['mat_khau'] = 'Mật khẩu không được để trống';
            }

            if (empty($errors)) {
                // Mã hóa mật khẩu
                $password = password_hash($mat_khau, PASSWORD_BCRYPT);

                // Thêm tài khoản
                $this->modelTaiKhoan->insertTaiKhoan($ho_ten, $email, $so_dien_thoai, $password, 2);
                header('Location: ' . BASE_URL);
                exit();
            } else {
                // Lưu lỗi vào session
                $_SESSION['error'] = $errors;
                header('Location: ' . BASE_URL . '?act=form-them');
                exit();
            }
        }
    }

    public function detailTaiKhoan()
    {
        $id = $_SESSION['user']['id'] ?? null;

        if ($id) {
            // Lấy thông tin tài khoản từ model
            $thongTin = $this->modelTaiKhoan->getDetailTaiKhoan($id);
            $listDanhMuc = $this->modelSanPham->getAllDanhMuc();

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $errors = [];

                // var_dump($_POST);
                // die();

                if (isset($_POST['update_info'])) {
                    // Dữ liệu từ form sửa thông tin
                    $ho_ten = $_POST['ho_ten'] ?? '';
                    $anh_dai_dien = $_FILES['anh_dai_dien'] ?? null;
                    $email = $_POST['email'] ?? '';
                    $so_dien_thoai = $_POST['so_dien_thoai'] ?? '';
                    $gioi_tinh = $_POST['gioi_tinh'] ?? '';
                    $dia_chi = $_POST['dia_chi'] ?? '';
                    $ngay_sinh = $_POST['ngay_sinh'] ?? '';

                    // Kiểm tra và xử lý ảnh đại diện nếu có
                    $image_path = $thongTin['anh_dai_dien']; // Mặc định dùng ảnh cũ
                    if ($anh_dai_dien && $anh_dai_dien['error'] == 0) {
                        $image_path = 'uploads/' . basename($anh_dai_dien['name']);
                        if (!move_uploaded_file($anh_dai_dien['tmp_name'], $image_path)) {
                            $errors['anh_dai_dien'] = 'Không thể tải lên ảnh đại diện.';
                        }
                    }

                    // Kiểm tra các giá trị khác
                    if (empty($ho_ten)) {
                        $errors['ho_ten'] = 'Họ tên không được để trống.';
                    }
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $errors['email'] = 'Email không hợp lệ.';
                    }
                    if (!preg_match('/^0\d{9}$/', $so_dien_thoai)) {
                        $errors['so_dien_thoai'] = 'Số điện thoại phải bắt đầu bằng số 0 và có đúng 10 chữ số.';
                    }

                    // Kiểm tra email đã tồn tại
                    if ($this->modelTaiKhoan->isEmailOrPhoneExists('email', $email, $id)) {
                        $errors['email'] = 'Email đã tồn tại.';
                    }

                    // Kiểm tra số điện thoại đã tồn tại
                    if ($this->modelTaiKhoan->isEmailOrPhoneExists('so_dien_thoai', $so_dien_thoai, $id)) {
                        $errors['so_dien_thoai'] = 'Số điện thoại đã tồn tại.';
                    }

                    // Nếu không có lỗi, tiến hành cập nhật
                    if (empty($errors)) {
                        $this->modelTaiKhoan->updateTaiKhoan($id, $ho_ten, $image_path, $email, $so_dien_thoai, $gioi_tinh, $dia_chi, $ngay_sinh);
                        $_SESSION['success'] = "Cập nhật thông tin cá nhân thành công!";
                    } else {
                        $_SESSION['error'] = $errors;
                    }
                }

                if (isset($_POST['update_password'])) {
                    // Dữ liệu từ form đổi mật khẩu
                    $old_pass = $_POST['old_pass'] ?? '';
                    $new_pass = $_POST['new_pass'] ?? '';
                    $confirm_pass = $_POST['confirm_pass'] ?? '';

                    // Kiểm tra mật khẩu cũ
                    if (!password_verify($old_pass, $thongTin['mat_khau'])) {
                        $errors['old_pass'] = 'Mật khẩu cũ không chính xác.';
                    }
                    if ($new_pass !== $confirm_pass) {
                        $errors['confirm_pass'] = 'Mật khẩu xác nhận không khớp.';
                    }
                    if (strlen($new_pass) < 6) {
                        $errors['new_pass'] = 'Mật khẩu mới phải có ít nhất 6 ký tự.';
                    }

                    // Nếu không có lỗi, cập nhật mật khẩu
                    if (empty($errors)) {
                        $hashPass = password_hash($new_pass, PASSWORD_BCRYPT);
                        $this->modelTaiKhoan->resetPassword($id, $hashPass);
                        $_SESSION['success'] = "Đổi mật khẩu thành công!";
                    } else {
                        $_SESSION['error'] = $errors;
                    }
                }

                // Refresh trang sau khi xử lý
                header('Location: ' . BASE_URL . '?act=detail-tai-khoan');
                exit();
            }
            //     // Hiển thị trang chi tiết tài khoản
            require_once './views/taikhoan/detail.php';
        } else {
            $_SESSION['error'] = "Không tìm thấy thông tin người dùng trong session.";
            header('Location: ' . BASE_URL);
            exit();
        }
    }

    public function updateTaiKhoan()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];
            $id = $_SESSION['user']['id'] ?? null;
            $thongTin = $this->modelTaiKhoan->getDetailTaiKhoan($id);
            $listDanhMuc = $this->modelSanPham->getAllDanhMuc();



            // var_dump($_POST);
            // die();

            if (isset($_POST['update_info'])) {
                // Dữ liệu từ form sửa thông tin
                $ho_ten = $_POST['ho_ten'] ?? '';
                $anh_dai_dien = $_FILES['anh_dai_dien                                                                  '] ?? null;
                $email = $_POST['email'] ?? '';
                $so_dien_thoai = $_POST['so_dien_thoai'] ?? '';
                $gioi_tinh = $_POST['gioi_tinh'] ?? '';
                $dia_chi = $_POST['dia_chi'] ?? '';
                $ngay_sinh = $_POST['ngay_sinh'] ?? '';

                // Kiểm tra và xử lý ảnh đại diện nếu có
                $image_path = $thongTin['anh_dai_dien']; // Mặc định dùng ảnh cũ
                if ($anh_dai_dien && $anh_dai_dien['error'] == 0) {
                    $image_path = '.uploads/' . basename($anh_dai_dien['name']);
                    if (!move_uploaded_file($anh_dai_dien['tmp_name'], $image_path)) {
                        $errors['anh_dai_dien'] = 'Không thể tải lên ảnh đại diện.';
                    }
                }

                // Kiểm tra các giá trị khác
                if (empty($ho_ten)) {
                    $errors['ho_ten'] = 'Họ tên không được để trống.';
                }
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors['email'] = 'Email không hợp lệ.';
                }
                if (!preg_match('/^0\d{9}$/', $so_dien_thoai)) {
                    $errors['so_dien_thoai'] = 'Số điện thoại phải bắt đầu bằng số 0 và có đúng 10 chữ số.';
                }

                // Kiểm tra email đã tồn tại
                if ($this->modelTaiKhoan->isEmailOrPhoneExists('email', $email, $id)) {
                    $errors['email'] = 'Email đã tồn tại.';
                }

                // Kiểm tra số điện thoại đã tồn tại
                if ($this->modelTaiKhoan->isEmailOrPhoneExists('so_dien_thoai', $so_dien_thoai, $id)) {
                    $errors['so_dien_thoai'] = 'Số điện thoại đã tồn tại.';
                }

                // Nếu không có lỗi, tiến hành cập nhật
                if (empty($errors)) {
                    $this->modelTaiKhoan->updateTaiKhoan($id, $ho_ten, $image_path, $email, $so_dien_thoai, $gioi_tinh, $dia_chi, $ngay_sinh);
                    $_SESSION['success'] = "Cập nhật thông tin cá nhân thành công!";
                } else {
                    $_SESSION['error'] = $errors;
                }
            }

            if (isset($_POST['update_password'])) {
                // Dữ liệu từ form đổi mật khẩu
                $old_pass = $_POST['old_pass'] ?? '';
                $new_pass = $_POST['new_pass'] ?? '';
                $confirm_pass = $_POST['confirm_pass'] ?? '';

                // Kiểm tra mật khẩu cũ
                if (!password_verify($old_pass, $thongTin['mat_khau'])) {
                    $errors['old_pass'] = 'Mật khẩu cũ không chính xác.';
                }
                if ($new_pass !== $confirm_pass) {
                    $errors['confirm_pass'] = 'Mật khẩu xác nhận không khớp.';
                }
                if (strlen($new_pass) < 6) {
                    $errors['new_pass'] = 'Mật khẩu mới phải có ít nhất 6 ký tự.';
                }

                // Nếu không có lỗi, cập nhật mật khẩu
                if (empty($errors)) {
                    $hashPass = password_hash($new_pass, PASSWORD_BCRYPT);
                    $this->modelTaiKhoan->resetPassword($id, $hashPass);
                    $_SESSION['success'] = "Đổi mật khẩu thành công!";
                } else {
                    $_SESSION['error'] = $errors;
                }
            }

            // Refresh trang sau khi xử lý
            header('Location: ' . BASE_URL . '?act=detail-tai-khoan');
            exit();
        }
    }
}
