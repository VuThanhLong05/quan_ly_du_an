<?php include './views/layout/header.php'; ?>
<?php include './views/layout/menu.php'; ?>

<main>
    <!-- Breadcrumb Area Start -->
    <div class="breadcrumb-area py-4 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-wrap">
                        <nav aria-label="breadcrumb">
                            <ul class="breadcrumb m-0">
                                <li class="breadcrumb-item">
                                    <a href="<?= BASE_URL ?>" class="text-decoration-none text-primary">
                                        <i class="fa fa-home"></i> Trang chủ
                                    </a>
                                </li>
                                <li class="breadcrumb-item active text-dark" aria-current="page">Danh sách sản phẩm</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Area End -->

    <!-- Shop Main Wrapper Start -->
    <div class="shop-main-wrapper section-padding pb-0">
        <div class="container">
            <div class="row">
                <!-- Sidebar for filters -->
                <div class="col-lg-3">
                    <div class="shop-sidebar p-4 bg-white shadow-sm rounded">
                        <h3 class="widget-title mb-3 text-uppercase">Tìm kiếm</h3>
                        <form action="<?= BASE_URL ?>" method="get">
                            <!-- Giữ tham số act để định tuyến -->
                            <input type="hidden" name="act" value="tim-kiem">
                            <!-- Lọc theo danh mục -->
                            <div class="form-group mb-3">
                                <label for="category" class="form-label">Danh mục:</label>
                                <select name="danh_muc" id="category" class="form-control">
                                    <option value="all">Tất cả</option>
                                    <?php foreach ($listDanhMuc as $danhMuc): ?>
                                        <option value="<?= $danhMuc['id'] ?>" <?= isset($_GET['danh_muc']) && $_GET['danh_muc'] == $danhMuc['id'] ? 'selected' : '' ?>>
                                            <?= $danhMuc['ten_danh_muc'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <br>
                            <!-- Lọc theo mức giá -->
                            <div class="form-group mb-3">
                                <label for="price-range" class="form-label">Mức giá:</label>
                                <select name="muc_gia" id="price-range" class="form-control">
                                    <option value="all">Tất cả</option>
                                    <option value="0-500000" <?= isset($_GET['muc_gia']) && $_GET['muc_gia'] == '0-500000' ? 'selected' : '' ?>>
                                        Dưới 500,000 VNĐ
                                    </option>
                                    <option value="500000-1000000" <?= isset($_GET['muc_gia']) && $_GET['muc_gia'] == '500000-1000000' ? 'selected' : '' ?>>
                                        500,000 - 1,000,000 VNĐ
                                    </option>
                                    <option value="1000000-2000000" <?= isset($_GET['muc_gia']) && $_GET['muc_gia'] == '1000000-2000000' ? 'selected' : '' ?>>
                                        1,000,000 - 2,000,000 VNĐ
                                    </option>
                                </select>
                            </div>
                            <br>
                            <!-- Lọc theo sắp xếp -->
                            <div class="form-group mb-3">
                                <label for="sort" class="form-label">Sắp xếp:</label>
                                <select name="sap_xep" id="sort" class="form-control">
                                    <option value="asc" <?= isset($_GET['sap_xep']) && $_GET['sap_xep'] == 'asc' ? 'selected' : '' ?>>Giá tăng dần</option>
                                    <option value="desc" <?= isset($_GET['sap_xep']) && $_GET['sap_xep'] == 'desc' ? 'selected' : '' ?>>Giá giảm dần</option>
                                </select>
                            </div>
                            <br><br>
                            <!-- Nút tìm kiếm -->
                            <button type="submit" class="btn btn-primary w-100 rounded-pill shadow-lg" style="height: 35px;">
                                <i class="fa fa-search"></i> Tìm kiếm
                            </button>
                        </form>

                    </div>
                </div>

                <!-- Product List -->
                <div class="col-lg-9">
                    <div class="row">
                        <?php if (!empty($listSanPham)): ?>
                            <?php foreach ($listSanPham as $sanPham): ?>
                                <div class="col-md-4">
                                    <div class="product-item bg-white shadow-sm rounded p-3 position-relative">
                                        <figure class="product-thumb mb-3">
                                            <a href="<?= BASE_URL . '?act=chi-tiet-san-pham&id_san_pham=' . $sanPham['id'] ?>" class="d-block">
                                                <img class="img-fluid rounded" src="<?= BASE_URL . $sanPham['hinh_anh'] ?>" alt="<?= $sanPham['ten_san_pham'] ?>">
                                            </a>
                                        </figure>
                                        <div class="product-caption text-center">
                                            <h6 class="product-name mb-2">
                                                <a href="<?= BASE_URL . '?act=chi-tiet-san-pham&id_san_pham=' . $sanPham['id'] ?>" class="text-decoration-none text-dark">
                                                    <?= $sanPham['ten_san_pham'] ?>
                                                </a>
                                            </h6>
                                            <div class="price-box">
                                                <?php if ($sanPham['gia_khuyen_mai']): ?>
                                                    <span class="price-regular text-success fw-bold"><?= number_format($sanPham['gia_khuyen_mai']) ?> đ</span>
                                                    <span class="price-old text-muted"><del><?= number_format($sanPham['gia_san_pham']) ?> đ</del></span>
                                                <?php else: ?>
                                                    <span class="price-regular text-primary fw-bold"><?= number_format($sanPham['gia_san_pham']) ?> đ</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-center text-muted">Không tìm thấy sản phẩm phù hợp.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Shop Main Wrapper End -->
</main>

<?php include './views/layout/footer.php'; ?>