-- TẠO CƠ SỞ DỮ LIỆU
CREATE DATABASE IF NOT EXISTS travelshare_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE travelshare_db;

-- Bảng người dùng
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    avatar VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bảng bài đăng
CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    title VARCHAR(255) NOT NULL,
    location VARCHAR(255),
    latitude DOUBLE,
    longitude DOUBLE,
    content TEXT,
    image VARCHAR(255),
    feeling VARCHAR(100) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_created_at (created_at)
);

-- Bảng lượt thích
CREATE TABLE likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    post_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    UNIQUE (user_id, post_id),
    INDEX idx_post_id (post_id),
    INDEX idx_user_id (user_id)
);

-- Bảng bình luận
CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    post_id INT,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    INDEX idx_post_id (post_id),
    INDEX idx_user_id (user_id)
);

-- Bảng thông báo
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_is_read (is_read)
);

-- Bảng lịch sử chỉnh sửa bài đăng
CREATE TABLE post_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT,
    editor_id INT NULL,
    old_title VARCHAR(255),
    old_content TEXT,
    edited_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (editor_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_post_id (post_id)
);

-- Thêm dữ liệu mẫu để test
INSERT INTO users (username, email, password) VALUES 
('admin', 'admin@example.com', 'admin123'),
('user1', 'user1@example.com', 'user123'),
('user2', 'user2@example.com', 'user123');

-- Thêm bài đăng mẫu
INSERT INTO posts (user_id, title, location, content, feeling) VALUES 
(1, 'Du lịch Hà Nội', 'Hà Nội, Việt Nam', 'Chuyến đi tuyệt vời đến thủ đô Hà Nội!', 'hạnh phúc'),
(2, 'Khám phá Sài Gòn', 'TP.HCM, Việt Nam', 'Sài Gòn thật sôi động và thú vị!', 'phấn khích'),
(3, 'Nghỉ dưỡng Đà Nẵng', 'Đà Nẵng, Việt Nam', 'Biển Đà Nẵng thật đẹp!', 'thư giãn');

-- Thêm lượt thích mẫu
INSERT INTO likes (user_id, post_id) VALUES 
(1, 2), (1, 3), (2, 1), (2, 3), (3, 1), (3, 2);

-- Thêm bình luận mẫu
INSERT INTO comments (user_id, post_id, content) VALUES 
(1, 2, 'Bài viết rất hay!'),
(2, 1, 'Cảm ơn bạn đã chia sẻ!'),
(3, 1, 'Tôi cũng muốn đi Hà Nội!');

ALTER TABLE users ADD COLUMN bio TEXT NULL AFTER avatar;