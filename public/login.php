<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/constants.php';

// Đây là trang riêng biệt cho login/register, không dùng header/footer chung
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - Đăng nhập / Đăng ký</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo CSS_URL; ?>auth.css">
    <link rel="icon" href="<?php echo IMAGES_URL; ?>favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="<?php echo IMAGES_URL; ?>favicon.png" type="image/x-icon">
</head>
<body>
    <?php
    // Bao gồm nội dung form đăng nhập/đăng ký
    include __DIR__ . '/../views/auth/auth_form.php';
    ?>

    <script src="<?php echo JS_URL; ?>auth.js"></script>
</body>
</html>