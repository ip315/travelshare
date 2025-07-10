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
    $user_id = intval($_SESSION['user_id']);
    
    if ($post_id <= 0) {
        throw new Exception('ID bài đăng không hợp lệ');
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
    
    // Kiểm tra user đã like bài đăng này chưa
    $check_like = $mysqli->prepare("SELECT id FROM likes WHERE user_id = ? AND post_id = ?");
    $check_like->bind_param("ii", $user_id, $post_id);
    $check_like->execute();
    $check_like->store_result();
    $already_liked = $check_like->num_rows > 0;
    $check_like->close();
    
    if ($already_liked) {
        // Nếu đã like thì unlike
        $delete_like = $mysqli->prepare("DELETE FROM likes WHERE user_id = ? AND post_id = ?");
        $delete_like->bind_param("ii", $user_id, $post_id);
        
        if ($delete_like->execute()) {
            $delete_like->close();
            
            // Lấy số lượng like mới
            $new_count = getLikeCount($post_id);
            
            echo json_encode([
                'success' => true,
                'action' => 'unliked',
                'likeCount' => $new_count,
                'message' => 'Đã bỏ thích bài đăng'
            ]);
        } else {
            throw new Exception('Lỗi khi bỏ thích bài đăng');
        }
    } else {
        // Nếu chưa like thì like
        $insert_like = $mysqli->prepare("INSERT INTO likes (user_id, post_id) VALUES (?, ?)");
        $insert_like->bind_param("ii", $user_id, $post_id);
        
        if ($insert_like->execute()) {
            $insert_like->close();
            
            // Lấy số lượng like mới
            $new_count = getLikeCount($post_id);
            
            echo json_encode([
                'success' => true,
                'action' => 'liked',
                'likeCount' => $new_count,
                'message' => 'Đã thích bài đăng'
            ]);
        } else {
            throw new Exception('Lỗi khi thích bài đăng');
        }
    }
    
} catch (Exception $e) {
    error_log("Like API Error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?> 