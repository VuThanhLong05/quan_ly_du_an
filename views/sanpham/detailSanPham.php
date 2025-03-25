<?php require_once './views/layout/header.php'; ?>
<?php require_once './views/layout/menu.php'; ?>


<main>
    <!-- Breadcrumb Area Start -->
    <div class="breadcrumb-area py-4">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-wrap">
                        <nav aria-label="breadcrumb">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>"><i class="fa fa-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="">Sản phẩm</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Chi tiết sản phẩm</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Area End -->

    <!-- Page Main Wrapper Start -->
    <div class="shop-main-wrapper section-padding pb-0">
        <div class="container">
            <div class="row">
                <!-- Product Details Wrapper Start -->
                <div class="col-lg-12 order-1 order-lg-2">
                    <div class="product-details-inner">
                        <div class="row">
                            <!-- Product Image Slider -->
                            <div class="col-lg-5">

                                <div class="product-large-slider">
                                    <?php foreach ($listAnhSanPham as $sanPham): ?>
                                        <div class="pro-large-img img-zoom">
                                            <img src="<?= $sanPham['hinh_anh'] ?>" alt="product-details" />

                                        </div>
                                    <?php endforeach ?>
                                </div>
                                <div class="pro-nav slick-row-10 slick-arrow-style">
                                    <?php foreach ($listAnhSanPham as $sanPham): ?>

                                        <div class="pro-nav-thumb">
                                            <img src="<?= $sanPham['hinh_anh'] ?>" alt="product-details" />
                                        </div>
                                    <?php endforeach ?>
                                </div>
                            </div>
                            <!-- Product Description -->
                            <div class="col-lg-7">
                                <div class="product-details-des">
                                    <div class="manufacturer-name mb-3">
                                        <p><?= $thongTinSanPham['ten_danh_muc']; ?></p>
                                    </div>
                                    <h3 class="product-name mb-3"><?= $thongTinSanPham['ten_san_pham']; ?></h3>
                                    <div class="ratings d-flex mb-3">
                                        <div class="pro-review">
                                            <?php $countComment = count(value: $listBinhLuan); ?>
                                            <span><?= $countComment . ' bình luận' ?></span>
                                        </div>
                                    </div>
                                    <div class="price-box mb-3">
                                        <?php if ($thongTinSanPham['gia_khuyen_mai']): ?>
                                            <span class="price-regular"><?= formatprice($thongTinSanPham['gia_khuyen_mai']) . 'đ'; ?></span>
                                            <span class="price-old"><del><?= formatprice($thongTinSanPham['gia_san_pham']) . 'đ'; ?></del></span>
                                        <?php else: ?>
                                            <span class="price-old"><del><?= formatprice($thongTinSanPham['gia_san_pham']) . 'đ'; ?></del></span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="availability mb-3">
                                        <i class="fa fa-check-circle"></i>
                                        <span> Sản phẩm còn trong kho: <?= $thongTinSanPham['so_luong'] ?></span>
                                    </div>
                                    <p class="pro-desc mb-4"><?= $thongTinSanPham['mo_ta'] ?></p>

                                    <form action="<?= BASE_URL . '?act=them-gio-hang' ?>" method="post">
                                        <div class="quantity-cart-box d-flex align-items-center mb-4">
                                            <h6 class="option-title mr-3">Số lượng:</h6>
                                            <div class="quantity">
                                                <input type="hidden" name="san_pham_id" value="<?= $thongTinSanPham['id'] ?>">
                                                <div class="pro-qty d-flex">
                                                    <input type="text" value="1" name="so_luong" class="form-control">
                                                </div>
                                            </div>
                                            <div class="action_link ml-4">
                                                <button class="btn btn-cart2">Thêm giỏ hàng</button>
                                            </div>
                                        </div>
                                    </form>

                                    <!-- Social Share Links -->
                                    <div class="like-icon">
                                        <a class="facebook" href="#"><i class="fa fa-facebook"></i> Like</a>
                                        <a class="twitter" href="#"><i class="fa fa-twitter"></i> Tweet</a>
                                        <a class="pinterest" href="#"><i class="fa fa-pinterest"></i> Save</a>
                                        <a class="google" href="#"><i class="fa fa-google-plus"></i> Share</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Reviews Start -->
                    <div class="product-details-reviews section-padding pb-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="product-review-info">
                                    <ul class="nav review-tab">
                                        <li>
                                            <a class="active" data-bs-toggle="tab" href="#tab_three">Bình luận (<?= $countComment; ?>)</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content reviews-tab">
                                        <div class="tab-pane fade show active" id="tab_three">
                                            <?php foreach ($listBinhLuan as $binhLuan): ?>
                                                <div class="total-reviews mb-3">
                                                    <div class="rev-avatar">
                                                        <img src="<?= BASE_URL . $binhLuan['anh_dai_dien'] ?>" alt="">
                                                    </div>
                                                    <div class="review-box">
                                                        <div class="post-author">
                                                            <p><span><?= $binhLuan['ho_ten'] ?> - </span><?= $binhLuan['ngay_dang'] ?> </p>
                                                        </div>
                                                        <p><?= $binhLuan['noi_dung'] ?></p>
                                                    </div>
                                                </div>
                                            <?php endforeach ?>


                                            <?php
                                            // Kiểm tra xem thông tin sản phẩm đã được truyền vào chưa
                                            if (isset($thongTinSanPham)) {
                                                $san_pham_id = $thongTinSanPham['id']; // Lấy ID sản phẩm từ dữ liệu sản phẩm
                                            } else {
                                                echo "Không tìm thấy thông tin sản phẩm.";
                                                exit;
                                            }
                                            ?>

                                            <!-- Form gửi bình luận -->
                                            <form action="<?= BASE_URL . '?act=form-them-binh-luan' ?>" method="post" class="review-form">
                                                <input type="hidden" name="san_pham_id" value="<?= $san_pham_id ?>"> <!-- ID sản phẩm -->
                                                <div class="form-group row">
                                                    <div class="col">
                                                        <label class="col-form-label"><span class="text-danger">*</span> Nội dung bình luận</label>
                                                        <textarea class="form-control" name="noi_dung" required></textarea>
                                                    </div>
                                                </div>
                                                <div class="buttons">
                                                    <button class="btn btn-sqr" type="submit">Bình luận</button>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Product Reviews End -->

                    <!-- Product Reviews Start -->
                    <div class="product-details-reviews section-padding pb-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="product-review-info">
                                    <ul class="nav review-tab">
                                        <li>
                                            <?php $countDanhGia = count(value: $listDanhGia); ?>
                                            <a class="active" data-bs-toggle="tab" href="#tab_three">Đánh giá (<?= $countDanhGia; ?>)</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content reviews-tab">
                                        <div class="tab-pane fade show active" id="tab_three">
                                            <?php foreach ($listDanhGia as $danhGia): ?>
                                                <div class="total-reviews mb-3">
                                                    <div class="rev-avatar">
                                                        <img src="<?= BASE_URL . $danhGia['anh_dai_dien'] ?>" alt="">
                                                    </div>
                                                    <div class="review-box">
                                                        <div class="post-author">
                                                            <p><span><?= $danhGia['ho_ten'] ?> - </span><?= $danhGia['ngay_dang'] ?> </p>
                                                        </div>
                                                        <p><?= $danhGia['noi_dung'] ?></p>
                                                    </div>
                                                </div>
                                            <?php endforeach ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Product Reviews End -->
                </div>
                <!-- Product Details Wrapper End -->
            </div>
        </div>
    </div>
    <!-- Page Main Wrapper End -->

    <!-- Related Products Area Start -->
    <section class="related-products section-padding">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title text-center mb-4">
                        <h2 class="title">Sản phẩm liên quan</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="product-carousel-4 slick-row-10 slick-arrow-style">
                        <?php foreach ($listSanPhamCungDanhMuc as $sanPham): ?>
                            <div class="product-item">
                                <figure class="product-thumb">
                                    <a href="<?= BASE_URL . '?act=chi-tiet-san-pham&id_san_pham=' . $sanPham['id'] ?>">
                                        <img class="pri-img" src="<?= BASE_URL . $sanPham['hinh_anh']; ?>" alt="product">
                                        <img class="sec-img" src="<?= BASE_URL . $sanPham['hinh_anh']; ?>" alt="product">
                                    </a>
                                    <div class="product-badge">
                                        <?php
                                        $ngayNhap = new DateTime($sanPham['ngay_nhap']);
                                        $ngayHienTai = new DateTime();
                                        $tinhNgay = $ngayHienTai->diff($ngayNhap);

                                        if ($tinhNgay->days <= 7): ?>
                                            <div class="product-label new">
                                                <span>Mới</span>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($sanPham['gia_khuyen_mai']): ?>
                                            <div class="product-label discount">
                                                <span>Giảm giá</span>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="cart-hover">
                                        <button class="btn btn-cart">Chi tiết</button>
                                    </div>
                                </figure>
                                <div class="product-caption text-center">
                                    <h6 class="product-name">
                                        <a href="<?= BASE_URL . '?act=chi-tiet-san-pham&id_san_pham=' . $sanPham['id'] ?>"><?= $sanPham['ten_san_pham'] ?></a>
                                    </h6>
                                    <div class="price-box">
                                        <?php if ($sanPham['gia_khuyen_mai']): ?>
                                            <span class="price-regular"><?= formatprice($sanPham['gia_khuyen_mai']) . 'đ'; ?></span>
                                            <span class="price-old"><del><?= formatprice($sanPham['gia_san_pham']) . 'đ'; ?></del></span>
                                        <?php else: ?>
                                            <span class="price-old"><del><?= formatprice($sanPham['gia_san_pham']) . 'đ'; ?></del></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Related Products Area End -->
</main>

<?php require_once './views/layout/miniCart.php'; ?>
<?php require_once './views/layout/footer.php'; ?>