<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/constants.php';

session_start();

// Kiểm tra nếu người dùng chưa đăng nhập, chuyển hướng về trang đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'public/login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - Trang cá nhân của <?php echo htmlspecialchars($_SESSION['username']); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo CSS_URL; ?>dashboard.css">
    <link rel="icon" href="<?php echo IMAGES_URL; ?>favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="<?php echo IMAGES_URL; ?>favicon.png" type="image/x-icon">
    <script>
        window.BASE_URL = '<?php echo BASE_URL; ?>';
        window.IMAGES_URL = '<?php echo IMAGES_URL; ?>';
    </script>
</head>
<body data-page="profile">
    <?php include __DIR__ . '/../views/pages/profile.php'; ?>
    <script src="<?php echo JS_URL; ?>sidebar.js"></script>
    <script src="<?php echo JS_URL; ?>profile.js"></script>
</body>
</html>