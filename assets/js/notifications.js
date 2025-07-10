document.addEventListener("DOMContentLoaded", function () {
    // Không có script cụ thể nào trong notifications.html ngoài việc hiển thị
    // Nếu có logic đánh dấu đã đọc hoặc tải thông báo từ API, sẽ thêm vào đây.

    // Ví dụ: Đánh dấu thông báo đã đọc khi click
    document.querySelectorAll('.notification-item').forEach(item => {
        item.addEventListener('click', function() {
            this.classList.remove('unread');
            const dot = this.querySelector('.notification-dot');
            if (dot) {
                dot.remove();
            }
            // TODO: Gửi request lên server để đánh dấu đã đọc
             fetch('/api/notifications/mark-read/' + notificationId, { method: 'POST' });
        });
    });
});