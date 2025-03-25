<!-- Start Header Area -->
<header class="header-area header-wide">
    <!-- main header start -->
    <div class="main-header d-none d-lg-block">

        <!-- header middle area start -->
        <div class="header-main-area sticky">
            <div class="container">
                <div class="row align-items-center position-relative">

                    <!-- start logo area -->
                    <div class="col-lg-2">
                        <div class="logo">
                            <a href="<?= BASE_URL ?>">
                                <img src="assets/img/logo/logo3.png" alt="Brand Logo">
                            </a>
                        </div>
                    </div>
                    <!-- start logo area -->

                    <!-- main menu area start -->
                    <div class="col-lg-6 position-static">
                        <div class="main-menu-area">
                            <div class="main-menu">
                                <!-- main menu navbar start -->
                                <nav class="desktop-menu">
                                    <ul>
                                        <li><a href="<?= BASE_URL ?>">Trang chủ</a>

                                        </li>
                                        <li><a href="<?= BASE_URL . '?act=danh-sach-san-pham' ?>">Sản phẩm <i class="fa fa-angle-down"></i></a>
                                            <ul class="dropdown">
                                                <?php foreach ($listDanhMuc as $danhMuc): ?>
                                                    <li><a href="<?= BASE_URL . '?act=tim-kiem&danh_muc=' . $danhMuc['id']; ?>"><?= $danhMuc['ten_danh_muc'] ?></a></li>
                                                <?php endforeach ?>
                                                <!-- <li><a href="<?= BASE_URL . '?act=danh-sach-san-pham&id_danh_muc=1' ?>">Danh mục 1 <i class="fa fa-angle-right"></i></a> -->
                                                <!-- Dropdown con -->
                                                <!-- <ul class="dropdown sub-menu"> -->
                                                <!-- <li><a href="<?= BASE_URL . '?act=chi-tiet-san-pham&id_san_pham=1' ?>">Sản phẩm 1.1</a></li>
                                                        <li><a href="<?= BASE_URL . '?act=chi-tiet-san-pham&id_san_pham=2' ?>">Sản phẩm 1.2</a></li>
                                                        <li><a href="<?= BASE_URL . '?act=chi-tiet-san-pham&id_san_pham=3' ?>">Sản phẩm 1.3</a></li> -->
                                                <!-- </ul>
                                                </li>a
                                                <li><a href="<?= BASE_URL . '?act=danh-sach-san-pham&id_danh_muc=2' ?>">Danh mục 2 <i class="fa fa-angle-right"></i></a>
                                                    <ul class="dropdown sub-menu">
                                                        <li><a href="<?= BASE_URL . '?act=chi-tiet-san-pham&id_san_pham=4' ?>">Sản phẩm 2.1</a></li>
                                                        <li><a href="<?= BASE_URL . '?act=chi-tiet-san-pham&id_san_pham=5' ?>">Sản phẩm 2.2</a></li>
                                                    </ul> -->
                                                <!-- </li>
                                                <li><a href="<?= BASE_URL . '?act=danh-sach-san-pham&id_danh_muc=3' ?>">Danh mục 3</a></li>
                                                <li><a href="<?= BASE_URL . '?act=danh-sach-san-pham' ?>">Tất cả sản phẩm</a></li>  -->
                                            </ul>
                                        </li>
                                        <li><a href="<?= BASE_URL . '?act=danh-sach-tin-tuc' ?>">Tin Tức</a></li>
                                        <li><a href="<?= BASE_URL . '?act=form-them-lien-he' ?>">Liên hệ</a></li>
                                        <li><a href="<?= BASE_URL . '?act=danh-sach-khuyen-mai' ?>">Khuyến mãi</a></li>
                                    </ul>
                                </nav>
                                <!-- main menu navbar end -->
                            </div>
                        </div>
                    </div>
                    <!-- main menu area end -->

                    <!-- mini cart area start -->
                    <div class="col-lg-4">
                        <div
                            class="header-right d-flex align-items-center justify-content-xl-between justify-content-lg-end">
                            <div class="header-search-container">
                                <button class="search-trigger d-xl-none d-lg-block"><i
                                        class="pe-7s-search"></i></button>
                                <form class="header-search-box d-lg-none d-xl-block">
                                    <input type="text" placeholder="Nhập tên sản phẩm" class="header-search-field">
                                    <button class="header-search-btn"><i class="pe-7s-search"></i></button>
                                </form>
                            </div>
                            <div class="header-configure-area">
                                <ul class="nav justify-content-end">
                                    <label for="">
                                        <?php
                                        if (isset($_SESSION['user_client'])) {
                                            // Truy cập vào tên người dùng từ mảng $_SESSION['user_client']
                                            echo $_SESSION['user_client']['ho_ten']; // Hoặc $_SESSION['user_client']['email'] nếu bạn muốn hiển thị email
                                        } ?>
                                    </label>
                                    <li class="user-hover">
                                        <a href="#">
                                            <i class="pe-7s-user"></i>
                                        </a>
                                        <ul class="dropdown-list">
                                            <?php
                                            if (!isset($_SESSION['user_client'])) { ?>
                                                <li><a href="<?= BASE_URL . '?act=login' ?>">Đăng nhập</a></li>
                                                <li><a href="<?= BASE_URL . '?act=form-them' ?>">Đăng kí</a></li>

                                            <?php } else { ?>

                                                <!-- <li><a href="login-register.html">Đăng kí</a></li> -->
                                                <li><a href="<?= BASE_URL . '?act=detail-tai-khoan' ?>">Tài khoản</a></li>
                                                <li><a href="<?= BASE_URL_ADMIN  ?>">Quản lý admin</a></li>
                                                <li><a href="<?= BASE_URL . '?act=logout' ?>">Đăng xuất</a></li>
                                                <li><a href="<?= BASE_URL . '?act=don-hang' ?>">Đơn hàng</a></li>
                                            <?php } ?>
                                        </ul>
                                    </li>

                                    <li>
                                        <a href="<?= BASE_URL . '?act=gio-hang' ?>">
                                            <i class="pe-7s-shopbag"></i>
                                            <div class="notification">
                                                <?php
                                                // Kiểm tra nếu giỏ hàng rỗng
                                                if (empty($chiTietGioHang)) {
                                                    echo '0';  // Nếu giỏ hàng trống thì hiển thị 0
                                                } else {
                                                    $uniqueProducts = [];  // Mảng lưu trữ các ID sản phẩm đã gặp
                                                    $totalQuantity = 0;  // Biến tính tổng số lượng sản phẩm

                                                    foreach ($chiTietGioHang as $gioHang) {
                                                        // Nếu sản phẩm chưa xuất hiện trong mảng uniqueProducts thì tính
                                                        if (!in_array($gioHang['san_pham_id'], $uniqueProducts)) {
                                                            $uniqueProducts[] = $gioHang['san_pham_id'];  // Thêm ID sản phẩm vào mảng
                                                            $totalQuantity += $gioHang['so_luong'];  // Cộng dồn số lượng sản phẩm
                                                        }
                                                    } ?>

                                                    <strong><?= $totalQuantity;  ?></strong>

                                                <?php
                                                    // In ra tổng số lượng sản phẩm duy nhất trong giỏ hàng
                                                }
                                                ?>
                                            </div>
                                        </a>
                                    </li>



                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- mini cart area end -->

                </div>
            </div>
        </div>
        <!-- header middle area end -->
    </div>
    <!-- main header start -->


</header>
<!-- end Header Area -->