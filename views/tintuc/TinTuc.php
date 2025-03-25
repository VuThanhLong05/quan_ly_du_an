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
                                <li class="breadcrumb-item"><a href="<?php BASE_URL ?>"><i class="fa fa-home"></i></a></li>
                                <li class="breadcrumb-item active" aria-current="page">Tin Tức </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb area end -->

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Danh sách tin tức</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <div class="container">
            <h1>Danh sách Tin tức</h1>
            <?php if (!empty($news)): ?>
                <?php foreach ($news as $item): ?>
                    <div class="news-item">
                        <h4><?= htmlspecialchars($item['tieu_de']) ?></h4> <br>
                        <img style="width: 10%;" src="<?= htmlspecialchars($item['anh']) ?>" alt="<?= htmlspecialchars($post['tieu_de']) ?>"
                            class="news-image"> <br> <br>
                        <p><?= nl2br(htmlspecialchars(substr($item['noi_dung'], 0, 200))) ?>...</p>
                        <p><small>Ngày đăng: <?= htmlspecialchars($item['ngay_dang']) ?></small></p>
                        <a href="index.php?act=chi-tiet-tin-tuc&id=<?= htmlspecialchars($item['id']) ?>" class="detail-btn">Xem
                            chi tiết</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Không có bài viết nào!</p>
            <?php endif; ?>
        </div>



        <?php require_once 'views/layout/footer.php'; ?>

        <style>
            /* Tiêu đề bài viết */
            .container h1 {
                font-size: 28px;
                color: #444;
                margin-bottom: 20px;
            }

            /* Nội dung bài viết */
            .content {
                font-size: 16px;
                line-height: 1.8;
                color: #555;
                margin-bottom: 20px;
            }

            /* Nút Xem chi tiết */
            .detail-btn {
                display: inline-block;
                margin-top: 10px;
                padding: 8px 15px;
                background-color: #5F9EA0;
                color: #fff;
                text-decoration: none;
                font-size: 14px;
                font-weight: bold;
                border-radius: 5px;
                position: absolute;
                /* Đặt vị trí */
                bottom: 15px;
                right: 15px;
                transition: background-color 0.3s ease;
            }

            .detail-btn:hover {
                background-color: #007BFF;
            }

            /* Đặt vị trí tin tức */
            .news-item {
                position: relative;
                /* Quan trọng để nút được đặt ở góc dưới */
                padding: 15px;
                margin-bottom: 15px;
                border-bottom: 1px solid #5F9EA0;
                transition: background-color 0.3s ease;
            }

            .news-item:hover {
                background-color: #f4f4f4;
            }
        </style>