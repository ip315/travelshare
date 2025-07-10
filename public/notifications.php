<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/constants.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - Thông báo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo CSS_URL; ?>dashboard.css">
    <link rel="icon" href="<?php echo IMAGES_URL; ?>favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="<?php echo IMAGES_URL; ?>favicon.png" type="image/x-icon">
</head>
<body data-page="notifications">
    <?php include __DIR__ . '/../views/pages/notifications.php'; ?>
    <script src="<?php echo JS_URL; ?>sidebar.js"></script>
    <script src="<?php echo JS_URL; ?>notifications.js"></script>
</body>
</html>