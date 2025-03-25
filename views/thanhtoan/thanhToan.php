<?php require_once './views/layout/header.php'; ?>
<?php require_once './views/layout/menu.php'; ?>

<main>
    <!-- breadcrumb area start -->
    <div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-wrap">
                        <nav aria-label="breadcrumb">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>"><i class="fa fa-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="shop.html">shop</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Thanh toán</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb area end -->

    <!-- checkout main wrapper start -->
    <div class="checkout-page-wrapper section-padding">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <!-- Checkout Login Coupon Accordion Start -->
                    <div class="checkoutaccordion" id="checkOutAccordion">


                        <div class="card">
                            <h6>Bạn có thêm mã giảm giá? <span data-bs-toggle="collapse" data-bs-target="#couponaccordion">Nhập code mã giảm giá</span></h6>
                            <div id="couponaccordion" class="collapse" data-parent="#checkOutAccordion">
                                <div class="card-body">
                                    <div class="cart-update-option">
                                        <div class="apply-coupon-wrapper">
                                            <form action="<?= BASE_URL . '?act=apply-coupon' ?>" method="post" class="d-block d-md-flex">
                                                <input type="text" name="coupon_code" placeholder="Nhập mã giảm giá" required />
                                                <button type="submit" class="btn btn-sqr">Nhập mã</button>
                                            </form>


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Checkout Login Coupon Accordion End -->
                </div>
            </div>
            <form action="<?= BASE_URL . '?act=xu-ly-thanh-toan' ?>" method="post">
                <div class="row">
                    <!-- Checkout Billing Details -->
                    <div class="col-lg-6">
                        <div class="checkout-billing-details-wrap">
                            <h5 class="checkout-title">Thông tin người nhận</h5>
                            <div class="billing-form-wrap">
                                <!-- <h2>Thông tin người nhận</h2> -->
                                <div class="form-group">
                                    <label for="ten_nguoi_nhan" class="required">Tên người nhận</label>
                                    <input type="text" id="ten_nguoi_nhan" name="ten_nguoi_nhan"
                                        value="<?= $_SESSION['user_client']['ho_ten'] ?>"
                                        placeholder="Tên người nhận" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="email" class="required">Email</label>
                                    <input type="email" id="email_nguoi_nhan" name="email_nguoi_nhan"
                                        value="<?= $_SESSION['user_client']['email'] ?>"
                                        placeholder="Email người nhận" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="sdt_nguoi_nhan" class="required">Số điện thoại</label>
                                    <input type="text" id="sdt_nguoi_nhan" name="sdt_nguoi_nhan"
                                        value="<?= $_SESSION['user_client']['so_dien_thoai'] ?>"
                                        placeholder="Số điện thoại" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="dia_chi_nguoi_nhan" class="required">Địa chỉ</label>
                                    <input type="text" id="dia_chi_nguoi_nhan" name="dia_chi_nguoi_nhan"
                                        value="<?= $_SESSION['user_client']['dia_chi'] ?>"
                                        placeholder="Địa chỉ" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="ghi_chu">Ghi chú</label>
                                    <textarea name="ghi_chu" id="ghi_chu" cols="30" rows="3" class="form-control" placeholder="Vui lòng nhập ghi chú"></textarea>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Order Summary Details -->
                    <div class="col-lg-6">
                        <div class="order-summary-details">
                            <h5 class="checkout-title">Thông tin giỏ hàng</h5>
                            <div class="order-summary-content">
                                <!-- Order Summary Table -->
                                <div class="order-summary-table table-responsive text-center">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Sản phẩm</th>
                                                <th>Hình ảnh sản phẩm</th>
                                                <th>Tổng</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $tong_tien_gio_hang = 0;
                                            foreach ($chiTietGioHang as $sanPham):
                                            ?>
                                                <tr>
                                                    <td class="pro-title">
                                                        <?= $sanPham['ten_san_pham'] ?>
                                                        <strong>x <?= $sanPham['so_luong'] ?></strong>
                                                    </td>
                                                    <td class="pro-thumbnail">
                                                        <a href="#">
                                                            <img style="width: 30%;" src="<?= BASE_URL . $sanPham['hinh_anh'] ?>" alt="<?= $sanPham['ten_san_pham'] ?>" />
                                                        </a>
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
                                                            echo formatprice($tong_tien) . ' VNĐ';
                                                            ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="2">Tổng tiền sản phẩm:</td>
                                                <td><strong><?= formatprice($tong_tien_gio_hang) ?> VNĐ</strong></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">Phí vận chuyển:</td>
                                                <td class="d-flex justify-content-center">
                                                    <ul class="shipping-type">
                                                        <li>
                                                            <div class="">
                                                                <label class="custom-control-label" for="flatrate"><?= formatprice(50000) . ' VNĐ' ?></label>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    Khuyến mãi
                                                    <?php if (isset($_SESSION['coupon_code'])): ?>
                                                        <?= htmlspecialchars($_SESSION['coupon_code']) ?>
                                                    <?php else: ?>
                                                        (Không có)
                                                        <?php endif; ?>:
                                                </td>
                                                <td>
                                                    <strong>
                                                        <?= isset($_SESSION['discount']) ? '- ' . formatprice($_SESSION['discount']) . ' VNĐ' : '0 VNĐ'; ?>
                                                    </strong>
                                                </td>
                                            </tr>



                                            <tr>
                                                <td colspan="2">Tổng thanh toán:</td>
                                                <td>
                                                    <strong>
                                                        <?php
                                                        // Tính tổng thanh toán sau khi giảm giá
                                                        $tong_tien_thanh_toan = max(0, $tong_tien_gio_hang + 50000 - (isset($_SESSION['discount']) ? $_SESSION['discount'] : 0));
                                                        ?>
                                                        <input type="hidden" name="tong_tien" value="<?= $tong_tien_thanh_toan ?>"> <!-- Lưu tổng tiền vào form -->
                                                        <strong><?= formatprice($tong_tien_thanh_toan) . ' VNĐ'; ?></strong>
                                                    </strong>
                                                </td>
                                            </tr>

                                        </tfoot>

                                    </table>

                                </div>
                                <!-- Order Payment Method -->
                                <div class="order-payment-method">
                                    <div class="single-payment-method show">
                                        <div class="payment-method-name">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="cashon" name="payUrl" value="1" class="custom-control-input" checked />
                                                <label class="custom-control-label" for="cashon">Thanh toán khi nhận hàng</label>
                                            </div>
                                        </div>
                                        <div class="payment-method-details" data-method="cash">
                                            <p>Thanh toán bằng tiền mặt khi nhận hàng.</p>
                                        </div>
                                    </div>

                                    <div class="single-payment-method">
                                        <div class="payment-method-name">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="paypalpayment" name="payUrl" value="2" class="custom-control-input" />
                                                <label class="custom-control-label" for="paypalpayment">Thanh Toán Momo <img src="https://cdn.haitrieu.com/wp-content/uploads/2022/10/Logo-MoMo-Square-768x768.png" class="img-fluid paypal-card" alt="Paypal" /></label>
                                            </div>
                                        </div>
                                        <div class="payment-method-details" data-method="paypal">
                                            <p>Thanh toán qua Momo; bạn có thể thanh toán bằng thẻ tín dụng nếu bạn không có tài khoản Momo.</p>
                                        </div>
                                    </div>
                                    <div class="summary-footer-area">
                                        <div class="custom-control custom-checkbox mb-20">
                                            <input type="checkbox" class="custom-control-input" id="terms" required />
                                            <label class="custom-control-label" for="terms">Tôi đã đọc và đồng ý với các điều khoản và điều kiện của trang web.</label>
                                        </div>
                                        <button type="submit" class="btn btn-sqr">Tiến hành đặt hàng</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
    <!-- checkout main wrapper end -->
</main>

<?php require_once './views/layout/miniCart.php'; ?>
<?php require_once './views/layout/footer.php'; ?>