<?php require_once 'views/layout/header.php'; ?>

<?php require_once 'views/layout/menu.php'; ?>

<main>
    <!-- breadcrumb area start -->

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= htmlspecialchars($post['tieu_de']) ?></title>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <div class="container">
            <?php if (!empty($post['anh'])): ?>
                <div class="image-container">
                    <img src="<?= htmlspecialchars($post['anh']) ?>" alt="<?= htmlspecialchars($post['tieu_de']) ?>"
                        class="news-image">
                    <div class="conten">
                        <h1><?= htmlspecialchars($post['tieu_de']) ?></h1>
                        <div class="content">
                            <?= nl2br(htmlspecialchars($post['noi_dung'])) ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <br>
            <div class="moo">
                <p><strong>Ngày đăng:</strong> <?= formatDate($post['ngay_dang']) ?></p>
                <a href="index.php?act=danh-sach-tin-tuc" class="back-btn">Quay lại</a>
            </div>

        </div>
    </body>
    <style>
        /* Container chi tiết bài viết */
        .container {
            max-width: 1400px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            color: #333;
        }

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

        /* Nút quay lại */
        .back-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007BFF;
            color: #fff;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .back-btn:hover {
            background-color: #5F9EA0;
        }

        .news-image {
            width: 500px;
            height: 500px;
        }

        .image-container {
            display: flex;
            justify-content: left;
            /* justify-content: space-between; */
        }

        .conten {
            margin-left: 20px;
        }

        .moo {
            display: flex;
            /* flex-direction: column; */
            justify-content: space-between;
        }
    </style>
    <?php require_once 'views/layout/footer.php'; ?>