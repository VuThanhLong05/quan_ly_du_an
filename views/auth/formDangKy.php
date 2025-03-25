<?php require_once 'views/layout/header.php'; ?>
<?php require_once 'views/layout/menu.php'; ?>

<main>
    <!-- breadcrumb area start -->
    <div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-wrap">
                        <nav aria-label="breadcrumb">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html"><i class="fa fa-home"></i></a></li>
                                <li class="breadcrumb-item active" aria-current="page">Thêm tài khoản</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb area end -->

    <!-- form area start -->
    <div class="container">
        <section class="content">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Thêm tài khoản</h3>
                        </div>
                        <!-- form start -->
                        <form action="<?= BASE_URL . '?act=them' ?>" method="post">
                            <div class="card-body">
                                <!-- Tên -->
                                <div class="form-group">
                                    <label for="ho_ten">Tên:</label>
                                    <input type="text" class="form-control" id="ho_ten" name="ho_ten"
                                        placeholder="Nhập họ tên" required>
                                    <?= isset($_SESSION['error']['ho_ten']) ? '<p class="text-danger">' . $_SESSION['error']['ho_ten'] . '</p>' : '' ?>
                                </div>
                                <!-- Email -->
                                <div class="form-group">
                                    <label>Email:</label>
                                    <input type="email" class="form-control" name="email" placeholder="Nhập email"
                                        required>
                                    <?php if (isset($_SESSION['error']['email'])): ?>
                                        <p class="text-danger"><?= $_SESSION['error']['email']; ?></p>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group">
                                    <label>Số điện thoại:</label>
                                    <input type="tel" class="form-control" name="so_dien_thoai"
                                        placeholder="Nhập số điện thoại" required>
                                    <?php if (isset($_SESSION['error']['so_dien_thoai'])): ?>
                                        <p class="text-danger"><?= $_SESSION['error']['so_dien_thoai']; ?></p>
                                    <?php endif; ?>
                                </div>

                                <?php
                                // Xóa lỗi sau khi hiển thị
                                unset($_SESSION['error']);
                                ?>

                                <!-- Mật khẩu -->
                                <div class="form-group">
                                    <label for="mat_khau">Mật khẩu:</label>
                                    <input type="password" class="form-control" id="mat_khau" name="mat_khau"
                                        placeholder="Nhập mật khẩu" required>
                                    <?= isset($_SESSION['error']['mat_khau']) ? '<p class="text-danger">' . $_SESSION['error']['mat_khau'] . '</p>' : '' ?>
                                </div>
                            </div>
                            <!-- Submit -->
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Thêm tài khoản</button>
                                <button type="button" class="btn btn-secondary" onclick="location.reload()">Làm
                                    mới</button>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- form area end -->
</main>

<?php require_once 'views/layout/footer.php'; ?>