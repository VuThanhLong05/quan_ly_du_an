<?php include './views/layout/header.php'; ?>
<?php include './views/layout/menu.php'; ?>

<main>
    <!-- Khu vực breadcrumb -->
    <div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-wrap">
                        <nav aria-label="breadcrumb">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="home.php"><i class="fa fa-home"></i></a></li>
                                <li class="breadcrumb-item active" aria-current="page">Khuyến mãi</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Khu vực chính -->
    <div class="shop-main-wrapper section-padding">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <!-- Danh sách khuyến mãi -->
                    <div class="row g-4">
                        <?php if (count($listKhuyenMai) > 0): ?>
                            <?php foreach ($listKhuyenMai as $km): ?>
                                <?php
                                // Kiểm tra khuyến mãi còn hạn hay không
                                $isExpired = strtotime($km['ngay_ket_thuc']) < time();
                                ?>
                                <div class="col-lg-4 col-md-6 col-sm-12" data-aos="fade-up">
                                    <div class="promo-item bg-white shadow rounded p-4">
                                        <h5 class="text-primary fw-bold"><?= htmlspecialchars($km['ten_khuyen_mai']) ?></h5>
                                        <p class="text-muted mb-3"><?= htmlspecialchars($km['mo_ta']) ?></p>
                                        <p class="mb-2"><strong>Thời gian:</strong>
                                            <?= date('d/m/Y', strtotime($km['ngay_bat_dau'])) ?> - <?= date('d/m/Y', strtotime($km['ngay_ket_thuc'])) ?>
                                        </p>
                                        <p class="text-success fw-bold fs-5 mb-3">Giá trị: <?= formatprice($km['gia_tri']) ?> VNĐ</p>
                                        <p class="promo-code text-center alert alert-secondary position-relative" style="word-wrap: break-word;">
                                            <?= htmlspecialchars($km['ma_khuyen_mai']) ?>
                                        </p>

                                        <?php if ($isExpired): ?>
                                            <button class="btn btn-secondary w-100 rounded-pill text-white mt-3" disabled>
                                                <i class="bi bi-clipboard"></i> Hết hạn
                                            </button>
                                        <?php else: ?>
                                            <button
                                                class="btn btn-gradient w-100 rounded-pill text-white mt-3"
                                                data-code="<?= htmlspecialchars($km['ma_khuyen_mai']) ?>"
                                                onclick="copyToClipboard(this)"
                                                data-bs-toggle="tooltip"
                                                title="Click để sao chép mã">
                                                <i class="bi bi-clipboard"></i> Sao chép mã
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-12">
                                <p class="text-center text-muted">Không tìm thấy khuyến mãi phù hợp.</p>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
</main>

<?php include './views/layout/footer.php'; ?>

<script>
    // Tooltip initialization
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(tooltipTrigger => {
        if (!tooltipTrigger.hasAttribute('disabled')) {
            new bootstrap.Tooltip(tooltipTrigger);
        }
    });

    function copyToClipboard(button) {
        const code = button.getAttribute('data-code');
        navigator.clipboard.writeText(code).then(() => {
            // Hiển thị thông báo thành công
            const successAlert = document.createElement('div');
            successAlert.className = 'alert alert-success alert-dismissible fade show';
            successAlert.role = 'alert';
            successAlert.innerHTML = `
            <strong>Thành công!</strong> Bạn đã sao chép mã khuyến mãi:
            <strong>${code}</strong>.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
            document.querySelector('.shop-main-wrapper .container .col-12').prepend(successAlert);

            setTimeout(() => successAlert.remove(), 1000);

            // Tooltip cập nhật trạng thái
            const tooltip = bootstrap.Tooltip.getInstance(button);
            tooltip.setContent({
                '.tooltip-inner': 'Đã sao chép!'
            });
            setTimeout(() => tooltip.setContent({
                '.tooltip-inner': 'Click để sao chép mã'
            }), 2000);
        });
    }
</script>

<style>
    .text-primary {
        color: #1d72b8 !important;
    }

    .btn-secondary {

        border: none;
        color: #fff;
        transition: all 0.1s ease;
        height: 45px;
    }

    .btn-gradient {
        background: linear-gradient(90deg, #22c1c3 0%, #2da851 100%);
        border: none;
        color: #fff;
        transition: all 0.1s ease;
        height: 45px;
    }

    .btn-gradient:hover {
        background: linear-gradient(90deg, #2da851 0%, #22c1c3 100%);
        transform: scale(1.05);
    }

    .promo-item {
        border: 1px solid #e9ecef;
        transition: all 0.3s ease-in-out;
    }

    .promo-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .promo-code {
        font-size: 1.2rem;
        background-color: #f8f9fa;
        border: 1px dashed #ced4da;
        text-align: center;
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 5px;
        overflow-wrap: break-word;
        /* Đảm bảo mã không bị cắt */
        word-wrap: break-word;
        max-width: 100%;
        /* Đảm bảo không bị cắt */
    }

    .pagination .page-item.active .page-link {
        background-color: #22c1c3;
        border-color: #22c1c3;
        color: #fff;
    }

    .pagination .page-link {
        color: #6c757d;
        transition: all 0.1s ease;
    }

    .pagination .page-link:hover {
        background-color: #e9ecef;
    }
</style>