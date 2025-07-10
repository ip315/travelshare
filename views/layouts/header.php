<?php require_once __DIR__ . '/../../config/app.php'; ?>
    <?php require_once __DIR__ . '/../../config/constants.php'; ?>
    <!DOCTYPE html>
    <html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="<?php echo CSS_URL; ?>normalize.css">
        <link rel="stylesheet" href="<?php echo CSS_URL; ?>global.css">
        <link rel="stylesheet" href="<?php echo CSS_URL; ?>layodut.css">
        <?php /* Nếu bạn có file all.min.css, hãy đặt nó vào assets/css/ và uncomment dòng dưới đây */ ?>
        <?php /* <link rel="stylesheet" href="<?php echo CSS_URL; ?>all.min.css"> */ ?>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
        <link rel="icon" href="<?php echo IMAGES_URL; ?>favicon.png" type="image/x-icon">
        <link rel="shortcut icon" href="<?php echo IMAGES_URL; ?>favicon.png" type="image/x-icon">
        <title><?php echo APP_NAME; ?></title>
    </head>
    <body>
        <div class="header">
            <div class="container">
                <div class="logo">
                    <a href="<?php echo BASE_URL; ?>">
                        <img src="<?php echo IMAGES_URL; ?>logo.png" alt="TravelShare Logo" class="logo-img">
                        <h1>Travel<span>Share.</span></h1>
                    </a>
                </div>
                <nav>
                    <ul>
                        <li><a href="<?php echo BASE_URL; ?>#Home" class="active">Trang chủ</a></li>
                        <li><a href="<?php echo BASE_URL; ?>#product-section">Điểm đến</a></li>
                        <li><a href="<?php echo BASE_URL; ?>#why-us">Về chúng tôi</a></li>
                        <li><a href="<?php echo BASE_URL; ?>#help-section">Dịch vụ</a></li>
                        <li><a href="<?php echo BASE_URL; ?>#popular-section ">Đề xuất</a></li>
                        <li><a href="<?php echo BASE_URL; ?>#subscribe-section">Liên hệ</a></li>
                        <li><a href="<?php echo BASE_URL; ?>login.php" class="login-btn">login/sign up</a></li>
                        </div>
                        <a href="<?php echo BASE_URL; ?>profile.php"><i class="fa-solid fa-user"></i></a>
                        <a href="#"><i class="fa-solid fa-heart"></i></a>
                    </ul>
                </nav>
            </div>
        </div>
