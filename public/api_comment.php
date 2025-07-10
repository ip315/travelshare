<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/db.php';
session_start();

header('Content-Type: application/json');

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Bạn chưa đăng nhập']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']);
    exit;
}

try {
    $post_id = intval($_POST['post_id'] ?? 0);
    $content = trim($_POST['content'] ?? '');
    $user_id = intval($_SESSION['user_id']);
    
    if ($post_id <= 0) {
        throw new Exception('ID bài đăng không hợp lệ');
    }
    
    if (empty($content)) {
        throw new Exception('Nội dung bình luận không được để trống');
    }
    
    if (strlen($content) > 1000) {
        throw new Exception('Nội dung bình luận quá dài (tối đa 1000 ký tự)');
    }
    
    // Kiểm tra bài đăng có tồn tại không
    $check_post = $mysqli->prepare("SELECT id FROM posts WHERE id = ?");
    $check_post->bind_param("i", $post_id);
    $check_post->execute();
    $check_post->store_result();
    
    if ($check_post->num_rows === 0) {
        throw new Exception('Bài đăng không tồn tại');
    }
    $check_post->close();
    
    // Thêm bình luận
    $insert_comment = $mysqli->prepare("INSERT INTO comments (user_id, post_id, content) VALUES (?, ?, ?)");
    $insert_comment->bind_param("iis", $user_id, $post_id, $content);
    
    if ($insert_comment->execute()) {
        $comment_id = $mysqli->insert_id;
        $insert_comment->close();
        
        // Lấy thông tin user để trả về
        $user_info = $mysqli->prepare("SELECT username, avatar FROM users WHERE id = ?");
        $user_info->bind_param("i", $user_id);
        $user_info->execute();
        $user_result = $user_info->get_result();
        $user_data = $user_result->fetch_assoc();
        $user_info->close();
        
        // Lấy số lượng comment mới
        $new_count = getCommentCount($post_id);
        
        echo json_encode([
            'success' => true,
            'message' => 'Đã thêm bình luận thành công',
            'comment' => [
                'id' => $comment_id,
                'content' => $content,
                'username' => $user_data['username'],
                'avatar' => $user_data['avatar'],
                'created_at' => date('Y-m-d H:i:s')
            ],
            'commentCount' => $new_count
        ]);
    } else {
        throw new Exception('Lỗi khi thêm bình luận');
    }
    
} catch (Exception $e) {
    error_log("Comment API Error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?> 