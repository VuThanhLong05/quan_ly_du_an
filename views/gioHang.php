<?php require_once 'layout/header.php'; ?>
<?php require_once 'layout/menu.php'; ?>

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
                                <li class="breadcrumb-item"><a href="shop.html">shop</a></li>
                                <li class="breadcrumb-item active" aria-current="page">cart</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb area end -->

    <!-- cart main wrapper start -->
    <div class="cart-main-wrapper section-padding">
        <div class="container">
            <div class="section-bg-color">
                <div class="row">
                    <div class="col-lg-12">
                        <!-- Cart Table Area -->
                        <div class="cart-table table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="pro-thumbnail">Ảnh sản phẩm</th>
                                        <th class="pro-title">Tên sản phẩm</th>
                                        <th class="pro-price">Giá tiền</th>
                                        <th class="pro-quantity">Số lượng</th>
                                        <th class="pro-subtotal">Tổng tiền</th>
                                        <th class="pro-remove">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($chiTietGioHang)): ?>
                                        <?php
                                        $tong_tien_gio_hang = 0;
                                        foreach ($chiTietGioHang as $sanPham):
                                        ?>
                                            <tr>
                                                <td class="pro-thumbnail"><a href="#"><img class="img-fluid" src="<?= BASE_URL . $sanPham['hinh_anh'] ?>" alt="Product" /></a></td>
                                                <td class="pro-title"><a href="#"><?= $sanPham['ten_san_pham'] ?></a></td>
                                                <td class="pro-price">
                                                    <span>
                                                        <?php
                                                        if ($sanPham['gia_khuyen_mai']) { ?>
                                                            <?= formatprice($sanPham['gia_khuyen_mai']) . ' VNĐ' ?>
                                                        <?php } else { ?>
                                                            <?= formatprice($sanPham['gia_san_pham']) . ' VNĐ' ?>
                                                        <?php } ?>
                                                    </span>
                                                </td>
                                                <!-- <td class="pro-quantity">
                                                    <div class="pro-qty d-flex">
                                                        <form action="<?= BASE_URL . '?act=them-gio-hang' ?>" method="post">
                                                            <div class="quantity-cart-box d-flex align-items-center mb-4">
                                                                <input type="hidden" name="san_pham_id" value="<?= $thongTinSanPham['id'] ?>">
                                                                <input type="text" value="<?= $sanPham['so_luong'] ?>" name="so_luong" class="form-control">
                                                            </div>
                                                        </form>
                                                    </div>
                                                </td> -->

                                                <td class="pro-quantity" style="text-align: center;">
                                                    <div class="">
                                                        <form action="<?= BASE_URL . '?act=them-gio-hang' ?>" method="post">
                                                            <div class="quantity-cart-box d-flex align-items-center mb-4">
                                                                <input type="hidden" name="san_pham_id" value="<?= $thongTinSanPham['id'] ?>">
                                                                <input style="text-align: center;" type="text" value="<?= $sanPham['so_luong'] ?>" name="so_luong" class="form-control">
                                                            </div>
                                                        </form>
                                                    </div>
                                                </td>
                                                <td class="pro-subtotal">
                                                    <span>
                                                        <?php
                                                        $tong_tien = 0;
                                                        if ($sanPham['gia_khuyen_mai']) {
                                                            $tong_tien = $sanPham['gia_khuyen_mai'] * $sanPham['so_luong'];
                                                        } else {
                                                            $tong_tien = $sanPham['gia_san_pham'] * $sanPham['so_luong'];
                                                        }
                                                        $tong_tien_gio_hang += $tong_tien;
                                                        echo formatprice($tong_tien);  ?>
                                                    </span>
                                                </td>
                                                <td class="pro-remove">
                                                    <a href="<?= BASE_URL . '?act=xoa-san-pham&id=' . $sanPham['san_pham_id'] ?>" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?');">
                                                        <i class="fa fa-trash-o"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center">Giỏ hàng của bạn đang trống</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Cart Update Option -->
                        <div class="cart-update-option d-block d-md-flex justify-content-between">
                            <!-- <div class="d-flex">
                                <form action="<?= BASE_URL . '?act=cap-nhat-gio-hang' ?>" method="post">
                                    <div class="cart-update" style="margin-left: 130px;">
                                        <input type="hidden" name="san_pham_id" value="<?= $sanPham['id'] ?>">
                                        <button type="submit" class="btn btn-sqr">Cập nhật giỏ hàng</button>
                                    </div>
                                </form>
                            </div> -->

                            <div class="cart">
                                <a href="<?= BASE_URL . '?act=lam-moi-gio-hang' ?>" class="btn btn-sqr" onclick="return confirm('Bạn có chắc muốn làm mới giỏ hàng?');">Làm mới giỏ hàng</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-5 ml-auto">
                        <!-- Cart Calculation Area -->
                        <div class="cart-calculator-wrapper">
                            <div class="cart-calculate-items">
                                <h6>Tổng đơn hàng</h6>
                                <div class="table-responsive">
                                    <table class="table">
                                        <tbody>
                                            <?php if (!empty($chiTietGioHang)): ?>
                                                <!-- Hiển thị tổng tiền sản phẩm -->
                                                <tr>
                                                    <td>Tổng tiền sản phẩm</td>
                                                    <td><?= formatprice($tong_tien_gio_hang) . ' VNĐ' ?></td>
                                                </tr>

                                                <!-- Hiển thị phí vận chuyển -->
                                                <tr>
                                                    <td>Vận chuyển</td>
                                                    <td>50.000 VNĐ</td>
                                                </tr>

                                                <!-- Hiển thị tổng thanh toán -->
                                                <tr class="total">
                                                    <td>Tổng thanh toán</td>
                                                    <td class="total-amount">
                                                        <?php
                                                        $total = $tong_tien_gio_hang + 50000; // Thêm phí vận chuyển
                                                        echo formatprice($total) . ' VNĐ';
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php else: ?>
                                                <tr>
                                                    <td>Tổng tiền sản phẩm</td>
                                                    <td>0 VNĐ</td>
                                                </tr>
                                                <tr>
                                                    <td>Vận chuyển</td>
                                                    <td>50.000 VNĐ</td>
                                                </tr>
                                                <tr class="total">
                                                    <td>Tổng thanh toán</td>
                                                    <td class="total-amount">50.000 VNĐ</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <a href="<?= BASE_URL . '?act=thanh-toan' ?>" class="btn btn-sqr d-block">Tiến hành đặt hàng</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    <!-- cart main wrapper end -->
</main>

<?php require_once 'layout/miniCart.php'; ?>
<?php require_once 'layout/footer.php'; ?>