document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('container');
    const registerBtn = document.getElementById('register');
    const loginBtn = document.getElementById('login');

    // Hàm hiển thị thông báo
    function showAlert(message, type) {
        // Xóa alert cũ nếu có
        const oldAlert = document.querySelector('.alert');
        if (oldAlert) oldAlert.remove();

        const alertDiv = document.createElement('div');
        alertDiv.classList.add('alert', `alert-${type}`);
        alertDiv.textContent = message;
        document.body.appendChild(alertDiv);

        setTimeout(() => {
            alertDiv.classList.add('fade-out');
            setTimeout(() => alertDiv.remove(), 500);
        }, 3000);
    }

    // Hiệu ứng gợn sóng cho các nút
    function createRipple(event) {
        const button = event.currentTarget;
        const circle = document.createElement("span");
        const diameter = Math.max(button.clientWidth, button.clientHeight);
        const radius = diameter / 2;

        circle.style.width = circle.style.height = `${diameter}px`;
        circle.style.left = `${event.clientX - button.getBoundingClientRect().left - radius}px`;
        circle.style.top = `${event.clientY - button.getBoundingClientRect().top - radius}px`;
        circle.classList.add("ripple");

        const ripple = button.getElementsByClassName("ripple")[0];
        if (ripple) ripple.remove();

        button.appendChild(circle);
    }

    // Thêm hiệu ứng cho tất cả button
    document.querySelectorAll('button').forEach(button => {
        button.addEventListener('click', createRipple);
    });

    // Chuyển đổi giữa đăng nhập và đăng ký
    if (registerBtn) {
        registerBtn.addEventListener('click', (e) => {
            e.preventDefault();
            container.classList.add("active");
        });
    }

    if (loginBtn) {
        loginBtn.addEventListener('click', (e) => {
            e.preventDefault();
            container.classList.remove("active");
        });
    }

    // Xử lý form đăng ký
    const signUpForm = document.getElementById('signUpForm');
    if (signUpForm) {
        signUpForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(signUpForm);
            
            try {
                const response = await fetch('controllers/AuthController.php?action=register', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showAlert(result.message, 'success');
                    // Reset form sau khi đăng ký thành công
                    signUpForm.reset();
                    // Tự động chuyển sang form đăng nhập
                    container.classList.remove("active");
                } else {
                    showAlert(result.message, 'error');
                }
            } catch (error) {
                showAlert('Có lỗi xảy ra khi đăng ký. Vui lòng thử lại.', 'error');
                console.error('Error:', error);
            }
        });
    }

    // Xử lý form đăng nhập
    const signInForm = document.getElementById('signInForm');
    if (signInForm) {
        signInForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(signInForm);
            
            try {
                const response = await fetch('controllers/AuthController.php?action=login', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showAlert(result.message, 'success');
                    setTimeout(() => {
                        window.location.href = result.redirect;
                    }, 1000);
                } else {
                    showAlert(result.message, 'error');
                }
            } catch (error) {
                showAlert('Có lỗi xảy ra khi đăng nhập. Vui lòng thử lại.', 'error');
                console.error('Error:', error);
            }
        });
    }
});