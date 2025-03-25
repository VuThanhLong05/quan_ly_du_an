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
                                <li class="breadcrumb-item"><a href="home.php"><i class="fa fa-home"></i></a></li>
                                <li class="breadcrumb-item active" aria-current="page">Đơn hàng</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb area end -->

    <div class="container">
        <?php if (!empty($donhang)): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Khách hàng</th>
                        <th>Mã đơn hàng</th>
                        <th>Ngày đặt</th>
                        <th>Sản phẩm</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($donhang as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['ten_nguoi_nhan']) ?></td>
                            <td><?= htmlspecialchars($item['ma_don_hang']) ?></td>
                            <td><?= htmlspecialchars($item['ngay_dat']) ?></td>
                            <td>
                                <?php if (!empty($item['san_pham'])): ?>
                                    <img src="<?= htmlspecialchars($item['san_pham'][0]['hinh_anh']) ?>" alt="Sản phẩm" width="50"
                                        height="50">
                                    <?= htmlspecialchars($item['san_pham'][0]['ten_san_pham']) ?>
                                    (<?= htmlspecialchars($item['san_pham'][0]['so_luong']) ?> x
                                    <?= htmlspecialchars(number_format($item['san_pham'][0]['gia_san_pham'], 0, ',', '.')) ?>VNĐ)
                                    <?php if (count($item['san_pham']) > 1): ?>
                                        <span class="badge badge-info">+<?= count($item['san_pham']) - 1 ?> sản phẩm khác</span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td>

                                <?php
                                // $tong_tien = 0;
                                // foreach ($item['san_pham'] as $san_pham) {
                                //     $tong_tien += $san_pham['gia_san_pham'] * $san_pham['so_luong'];
                                // }
                                // echo htmlspecialchars(number_format($tong_tien, 0, ',', '.')) . ' VNĐ';
                                ?>

                                <?= htmlspecialchars(number_format($item['tong_tien'])) ?>
                            </td>
                            <td><?= htmlspecialchars($item['trang_thai']) ?></td>
                            <td>
                                <a href="index.php?act=chi-tiet-don-hang&id=<?= htmlspecialchars($item['don_hang_id']) ?>"
                                    class="btn btn-primary btn-sm">Chi tiết</a>
                                <?php if ($item['trang_thai_id'] === 1): ?>
                                    <a href="index.php?act=huy-don-hang&id=<?= htmlspecialchars($item['don_hang_id']) ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này không?');">Hủy</a>
                                <?php elseif ($item['trang_thai_id'] === 9): ?>
                                    <span class="badge badge-secondary">Đã hủy</span>
                                <?php elseif ($item['trang_thai_id'] === 5): ?>
                                    <a href="index.php?act=da-nhan-don-hang&id=<?= htmlspecialchars($item['don_hang_id']) ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Bạn có chắc chắn đã nhận được đơn hàng này không ?');">Đã nhận</a>
                                <?php else: ?>
                                    <span class="badge badge-warning">Không thể hủy</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Không có đơn hàng nào!</p>
        <?php endif; ?>
    </div>
</main>

<style>
    .table img {
        object-fit: cover;
        border-radius: 5px;
    }

    .badge {
        padding: 5px 10px;
        border-radius: 5px;
    }

    .badge-secondary {
        background-color: #ccc;
        color: #fff;
    }

    .badge-warning {
        background-color: #f39c12;
        color: #fff;
    }

    .badge-info {
        background-color: #3498db;
        color: #fff;
    }

    .btn {
        padding: 5px 10px;
        border-radius: 5px;
        text-decoration: none;
        color: #fff;
    }

    .btn-primary {
        background-color: #3498db;
    }

    .btn-primary:hover {
        background-color: #2980b9;
    }

    .btn-danger {
        background-color: #e74c3c;
    }

    .btn-danger:hover {
        background-color: #c0392b;
    }
</style>

<?php require_once 'views/layout/footer.php'; ?>