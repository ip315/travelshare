<?php
// Nạp các file cấu hình, hằng số, v.v.
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/constants.php';

// Định nghĩa hằng số IMAGES_URL nếu cần
//define('IMAGES_URL', BASE_URL . 'public/images/');

// Hiển thị giao diện trang chủ
include __DIR__ . '/views/layouts/header.php';
include __DIR__ . '/views/homepage/index.php';
include __DIR__ . '/views/layouts/footer.php';