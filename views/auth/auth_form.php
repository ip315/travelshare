<?php require_once __DIR__ . '/../../config/app.php'; ?>
<?php require_once __DIR__ . '/../../config/constants.php'; ?>
<div class="container" id="container">
    <div class="form-container sign-up">
        <form action="" method="POST" id="signUpForm" enctype="multipart/form-data">
            <h1>Tạo tài khoản</h1>
            <div class="social-icons">
                <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
                <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
            </div>
            <span>hoặc sử dụng email để đăng ký</span>
            <input type="text" placeholder="Tên người dùng" name="username" required>
            <input type="email" placeholder="Email" name="email" required>
            <input type="password" placeholder="Mật khẩu" name="password" required>
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="avatar" style="display:block;margin-bottom:5px;color:#555;font-size:14px;">Ảnh đại diện</label>
                <input type="file" id="avatar" name="avatar" accept="image/*" style="padding:5px;border-radius:4px;border:1px solid #ccc;">
                <span id="avatar-filename" style="margin-left:10px;color:#555;font-style:italic;"></span>
            </div>
            <button type="submit">Đăng ký</button>
        </form>
    </div>
    <div class="form-container sign-in">
        <form action="" method="POST" id="signInForm">
            <h1>Đăng nhập</h1>
            <div class="social-icons">
                <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
                <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
            </div>
            <span>hoặc sử dụng email và mật khẩu của bạn</span>
            <input type="email" placeholder="Email" name="email" required>
            <input type="password" placeholder="Mật khẩu" name="password" required>
            <a href="#">Quên mật khẩu?</a>
            <button type="submit">Đăng nhập</button>
        </form>
    </div>
    <div class="toggle-container">
        <div class="toggle">
            <div class="toggle-panel toggle-left">
                <h1>Chào mừng trở lại!</h1>
                <p>Nhập thông tin cá nhân của bạn để sử dụng tất cả các tính năng của trang web</p>
                <button class="hidden" id="login">Đăng nhập</button>
            </div>
            <div class="toggle-panel toggle-right">
                <h1>Chào bạn!</h1>
                <p>Đăng ký với thông tin cá nhân của bạn để sử dụng tất cả các tính năng của trang web</p>
                <button class="hidden" id="register">Đăng ký</button>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var avatarInput = document.getElementById('avatar');
    if (avatarInput) {
        avatarInput.addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : '';
            document.getElementById('avatar-filename').textContent = fileName;
        });
    }
});
</script>