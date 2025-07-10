<?php
header('Content-Type: application/json; charset=utf-8');
session_start();
require_once __DIR__ . '/../../config/db.php';

$action = $_GET['action'] ?? '';

if ($action === 'get_user_profile') {
    if (!isset($_SESSION['user_data'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Chưa đăng nhập.'
        ]);
        exit;
    }
    $user = $_SESSION['user_data'];
    $user_id = intval($user['id']);
    // Lấy thông tin user từ DB (tránh lấy từ session cũ)
    $stmt = $mysqli->prepare("SELECT id, username, email, avatar, bio FROM users WHERE id = ?");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $userResult = $stmt->get_result();
    $userData = $userResult->fetch_assoc();
    $stmt->close();
    if (!$userData) {
        echo json_encode(['success' => false, 'message' => 'Không tìm thấy user.']);
        exit;
    }
    // Đếm số bài viết
    $post_count = 0;
    $stmt = $mysqli->prepare("SELECT COUNT(*) as total FROM posts WHERE user_id = ?");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->bind_result($post_count);
    $stmt->fetch();
    $stmt->close();
    // Lấy danh sách bài viết
    $posts = [];
    $stmt = $mysqli->prepare("SELECT id, title, content, image as image_url, created_at FROM posts WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
    $stmt->close();
    // Trả về dữ liệu
    echo json_encode([
        'success' => true,
        'user' => [
            'id' => $userData['id'],
            'username' => $userData['username'],
            'email' => $userData['email'],
            'avatar' => $userData['avatar'],
            'bio' => $userData['bio'],
            'postsCount' => $post_count,
            'followersCount' => 0, // Chưa có bảng follower
            'followingCount' => 0  // Chưa có bảng following
        ],
        'posts' => $posts
    ]);
    exit;
}
// Nếu không đúng action
http_response_code(400);
echo json_encode(['success' => false, 'message' => 'Invalid action']); 