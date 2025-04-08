<?php

// Biến môi trường, dùng chung toàn hệ thống
// Khai báo dưới dạng HẰNG SỐ để không phải dùng $GLOBALS

define('BASE_URL', 'http://localhost/quan_ly_du_an/');

// Đường dẫn vào phần admin
define('BASE_URL_ADMIN', 'http://localhost/quan_ly_du_an/admin/');
// Đường dẫn đến client

define('DB_HOST', 'localhost');
define('DB_PORT', 3306);
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'duan_1');  // Tên database

// define('PATH_ROOT'    , __DIR__ );
define('PATH_ROOT', __DIR__ . '/../');
