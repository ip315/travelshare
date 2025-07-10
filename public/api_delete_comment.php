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
    $comment_id = intval($_POST['comment_id'] ?? 0);
    $user_id = intval($_SESSION['user_id']);
    
    if ($comment_id <= 0) {
        throw new Exception('ID bình luận không hợp lệ');
    }
    
    // Kiểm tra bình luận có tồn tại không và lấy thông tin
    $check_comment = $mysqli->prepare("SELECT c.id, c.post_id, c.user_id, u.username FROM comments c JOIN users u ON c.user_id = u.id WHERE c.id = ?");
    $check_comment->bind_param("i", $comment_id);
    $check_comment->execute();
    $comment_result = $check_comment->get_result();
    
    if ($comment_result->num_rows === 0) {
        throw new Exception('Bình luận không tồn tại');
    }
    
    $comment_data = $comment_result->fetch_assoc();
    $check_comment->close();
    
    // Kiểm tra quyền xóa (chỉ user tạo bình luận hoặc admin mới được xóa)
    if ($comment_data['user_id'] != $user_id) {
        // Kiểm tra xem user hiện tại có phải admin không
        $check_admin = $mysqli->prepare("SELECT username FROM users WHERE id = ? AND username = 'admin'");
        $check_admin->bind_param("i", $user_id);
        $check_admin->execute();
        $check_admin->store_result();
        
        if ($check_admin->num_rows === 0) {
            throw new Exception('Bạn không có quyền xóa bình luận này');
        }
        $check_admin->close();
    }
    
    // Xóa bình luận
    $delete_comment = $mysqli->prepare("DELETE FROM comments WHERE id = ?");
    $delete_comment->bind_param("i", $comment_id);
    
    if ($delete_comment->execute()) {
        $delete_comment->close();
        
        // Lấy số lượng comment mới
        $new_count = getCommentCount($comment_data['post_id']);
        
        echo json_encode([
            'success' => true,
            'message' => 'Đã xóa bình luận thành công',
            'commentCount' => $new_count
        ]);
    } else {
        throw new Exception('Lỗi khi xóa bình luận');
    }
    
} catch (Exception $e) {
    error_log("Delete Comment API Error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?> 