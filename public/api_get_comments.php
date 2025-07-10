<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/db.php';
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']);
    exit;
}

try {
    $post_id = intval($_GET['post_id'] ?? 0);
    
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
    
    // Lấy danh sách bình luận
    $query = "
        SELECT 
            c.id,
            c.content,
            c.created_at,
            u.username,
            u.avatar
        FROM comments c
        JOIN users u ON c.user_id = u.id
        WHERE c.post_id = ?
        ORDER BY c.created_at ASC
    ";
    
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $comments = [];
    while ($row = $result->fetch_assoc()) {
        $comments[] = [
            'id' => $row['id'],
            'content' => $row['content'],
            'username' => $row['username'],
            'avatar' => $row['avatar'],
            'created_at' => $row['created_at']
        ];
    }
    $stmt->close();
    
    echo json_encode([
        'success' => true,
        'comments' => $comments,
        'total' => count($comments)
    ]);
    
} catch (Exception $e) {
    error_log("Get Comments API Error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?> 