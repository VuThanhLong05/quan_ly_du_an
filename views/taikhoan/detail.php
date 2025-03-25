<?php require_once 'views/layout/header.php'; ?>

<?php require_once 'views/layout/menu.php'; ?>


<!-- Content Wrapper. Contains page content -->
<div class="container">
    <!-- Hiển thị thông báo -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="row">
        <!-- Phần Avatar -->
        <div class="col-md-4 text-center">
            <img src="<?= BASE_URL . $thongTin['anh_dai_dien'] ?>" class="avatar img-circle" alt="avatar"
                style="width:100%"
                onerror="this.onerror=null; this.src='https://i0.wp.com/www.stignatius.co.uk/wp-content/uploads/2020/10/default-user-icon.jpg?w=415&ssl=1'">
            <h2 class="mt-2">MR: <?= htmlspecialchars($thongTin['ho_ten']); ?></h2>
        </div>

        <!-- Phần cập nhật thông tin -->
        <div class="col-md-8">
            <h3>Chỉnh sửa thông tin cá nhân</h3>
            <form action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="update_info" value="1">

                <div class="form-group">
                    <label>Họ tên:</label>
                    <input class="form-control" type="text" value="<?= htmlspecialchars($thongTin['ho_ten']); ?>"
                        name="ho_ten">
                </div>

                <div class="form-group">
                    <label>Ảnh đại diện:</label>
                    <input class="form-control" type="file" name="anh_dai_dien">
                </div>

                <div class="form-group">
                    <label>Email:</label>
                    <input class="form-control" type="text" value="<?= htmlspecialchars($thongTin['email']); ?>"
                        name="email">
                </div>

                <div class="form-group">
                    <label>Số điện thoại:</label>
                    <input class="form-control" type="text" value="<?= htmlspecialchars($thongTin['so_dien_thoai']); ?>"
                        name="so_dien_thoai">
                </div>

                <div class="form-group">
                    <label for="gioi_tinh">Giới tính:</label><br>
                    <select class="form-control" id="gioi_tinh" name="gioi_tinh" >
                        <option value="Nam" <?= $thongTin['gioi_tinh'] === 'Nam' ? 'selected' : ''; ?>>Nam</option>
                        <option value="Nữ" <?= $thongTin['gioi_tinh'] === 'Nữ' ? 'selected' : ''; ?>>Nữ</option>
                        <option value="Khác" <?= $thongTin['gioi_tinh'] === 'Khác' ? 'selected' : ''; ?>>Khác</option>
                    </select>
                </div><br>

                <div class="form-group">
                    <label>Ngày sinh:</label>
                    <input class="form-control" type="date" value="<?= htmlspecialchars($thongTin['ngay_sinh']); ?>"
                        name="ngay_sinh">
                </div>

                <div class="form-group">
                    <label>Địa chỉ:</label>
                    <input class="form-control" type="text" value="<?= $thongTin['dia_chi']; ?>"
                        name="dia_chi">
                </div>

                

                <button style="padding: 8px;" type="submit" class="btn btn-primary">Cập nhật</button>
            </form>
        </div>

        <!-- Phần đổi mật khẩu -->
        <div class="col-md-12 mt-4">
            <hr>
            <h3>Đổi mật khẩu</h3>
            <form method="POST">
                <input type="hidden" name="update_password" value="1">

                <div class="form-group">
                    <label for="old_pass">Mật khẩu cũ:</label>
                    <input type="password" name="old_pass" class="form-control">
                </div>

                <div class="form-group">
                    <label for="new_pass">Mật khẩu mới:</label>
                    <input type="password" name="new_pass" class="form-control">
                </div>

                <div class="form-group">
                    <label for="confirm_pass">Xác nhận mật khẩu mới:</label>
                    <input type="password" name="confirm_pass" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">Đổi mật khẩu</button>
            </form>
        </div>
    </div>
</div>

<?php require_once 'views/layout/footer.php'; ?>