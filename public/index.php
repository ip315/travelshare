<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/constants.php';

// Hiển thị giao diện trang chủ
include __DIR__ . '/../views/layouts/header.php';
include __DIR__ . '/../views/homepage/index.php';
include __DIR__ . '/../views/layouts/footer.php';
?>