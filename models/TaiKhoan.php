<?php
// session_start();
class TaiKhoan
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }
    public function isEmailExists($email)
    {
        $sql = "SELECT COUNT(*) FROM tai_khoans WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetchColumn() > 0;
    }

    public function isEmailOrPhoneExists($field, $value, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) as count FROM tai_khoans WHERE $field = :value";
        $params = [':value' => $value];

        if ($excludeId !== null) {
            $sql .= " AND id != :exclude_id";
            $params[':exclude_id'] = $excludeId;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();

        return $result['count'] > 0;
    }
    public function isPhoneExists($so_dien_thoai)
    {
        $sql = "SELECT COUNT(*) FROM tai_khoans WHERE so_dien_thoai = :so_dien_thoai";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':so_dien_thoai' => $so_dien_thoai]);
        return $stmt->fetchColumn() > 0;
    }
    public function checkExists($email, $so_dien_thoai)
    {
        $sql = "SELECT COUNT(*) as count FROM `tai_khoans` WHERE `email` = :email OR `so_dien_thoai` = :so_dien_thoai";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':so_dien_thoai', $so_dien_thoai);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }
    public function checkLogin($email, $mat_khau)
    {
        try {
            $sql = 'SELECT * FROM tai_khoans WHERE email = :email';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();

            if ($user && password_verify($mat_khau, $user['mat_khau'])) {
                // Lưu thông tin đăng nhập vào session
                $_SESSION['user'] = $user;  // Lưu thông tin người dùng vào session
                // var_dump($_SESSION['user']);  // Xem thông tin session 
                // die();
                if ($user['vai_tro'] == 1) { // Nếu vai trò là admin
                    return $user; // Trả về thông tin admin
                } elseif ($user['vai_tro'] == 2) { // Nếu vai trò là khách hàng
                    return $user; // Trả về thông tin khách hàng
                }
            } else {
                return 'Đăng nhập sai thông tin mật khẩu hoặc tài khoản';
            }
        } catch (Exception $e) {
            echo 'Lỗi: ' . $e->getMessage();
            return false;
        }
    }



    public function getTaiKhoanFromEmail($email)
    {
        try {
            $sql = 'SELECT * FROM tai_khoans WHERE email = :email';

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':email' => $email
            ]);

            return $stmt->fetch();
        } catch (Exception $e) {
            echo 'Lỗi: ' . $e->getMessage();
        }
    }

    //// đăng ký 
    // Hàm thêm tài khoản vào database

    public function getAllTaiKhoan($vai_tro)
    {
        try {
            $sql = 'SELECT * FROM tai_khoans WHERE vai_tro = :vai_tro';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':vai_tro' => $vai_tro]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo 'Lỗi: ' . $e->getMessage();
        }
    }

    public function insertTaiKhoan($ho_ten, $email, $so_dien_thoai, $password, $vai_tro)
    {
        try {
            $sql = 'INSERT INTO tai_khoans (ho_ten, email, so_dien_thoai, mat_khau, vai_tro)
                    VALUES (:ho_ten, :email, :so_dien_thoai, :mat_khau, :vai_tro)';
            $stmt = $this->conn->prepare($sql);

            // Gán tham số
            $stmt->bindParam(':ho_ten', $ho_ten, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':so_dien_thoai', $so_dien_thoai, PDO::PARAM_STR);
            $stmt->bindParam(':mat_khau', $password, PDO::PARAM_STR);
            $stmt->bindParam(':vai_tro', $vai_tro, PDO::PARAM_INT);

            // Thực thi câu lệnh
            if ($stmt->execute()) {
                return true;
            } else {
                // Trả về lỗi nếu xảy ra lỗi khi thực thi
                $errorInfo = $stmt->errorInfo();
                throw new Exception("Lỗi khi chèn tài khoản: " . $errorInfo[2]);
            }
        } catch (Exception $e) {
            // Xử lý ngoại lệ, ghi log hoặc hiển thị lỗi
            error_log('Lỗi insertTaiKhoan: ' . $e->getMessage());
            echo 'Lỗi: ' . $e->getMessage();
            return false;
        }
    }


    public function getDetailTaiKhoan($id)
    {
        try {
            $sql = 'SELECT * FROM tai_khoans WHERE id = :id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            echo 'Lỗi: ' . $e->getMessage();
        }
    }

    public function updateTaiKhoan($id, $ho_ten, $anh_dai_dien, $email, $so_dien_thoai, $gioi_tinh, $dia_chi, $ngay_sinh)
    {
        try {
            $sql = 'UPDATE tai_khoans SET 
                    ho_ten = :ho_ten, 
                    anh_dai_dien = :anh_dai_dien, 
                    email = :email, 
                    so_dien_thoai = :so_dien_thoai, 
                    gioi_tinh = :gioi_tinh, 
                    dia_chi = :dia_chi, 
                    ngay_sinh = :ngay_sinh 
                WHERE id = :id';

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':ho_ten' => $ho_ten,
                ':anh_dai_dien' => $anh_dai_dien,
                ':email' => $email,
                ':so_dien_thoai' => $so_dien_thoai,
                ':gioi_tinh' => $gioi_tinh,
                ':dia_chi' => $dia_chi,
                ':ngay_sinh' => $ngay_sinh,
                ':id' => $id
            ]);
            return true;
        } catch (Exception $e) {
            echo 'Lỗi: ' . $e->getMessage();
            return false;
        }
    }



    ///////////////

    public function getTaiKhoanFormEmail($email)
    {
        try {
            $sql = 'SELECT * FROM  tai_khoans WHERE email = :email';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':email' => $email,

            ]);
            return $stmt->fetch();
        } catch (Exception $e) {
            echo "Lỗi" . $e->getMessage();
        }
    }

    public function resetPassword($id, $mat_khau)
    {
        try {
            $sql = 'UPDATE tai_khoanS SET 
                    mat_khau = :mat_khau
                WHERE id = :id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':mat_khau' => $mat_khau,
                ':id' => $id

            ]);
            return true;
        } catch (Exception $e) {
            echo "Lỗi" . $e->getMessage();
        }
    }
}
