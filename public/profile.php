<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/db.php';

session_start();

// Kiểm tra nếu người dùng chưa đăng nhập, chuyển hướng về trang đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'public/login.php');
    exit();
}

// Lấy toàn bộ dữ liệu user (trừ mật khẩu) và lưu vào session['user_data']
$user_id = $_SESSION['user_id'];
$stmt = $mysqli->prepare("SELECT id, username, email, avatar, bio, created_at FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
$_SESSION['user_data'] = $user;
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