<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/db.php';
header('Content-Type: application/json');

try {
    // Lấy danh sách bài đăng với thông tin user và số lượng thích/bình luận
    $query = "
        SELECT 
            p.*,
            u.username,
            u.avatar,
            COUNT(DISTINCT l.id) as like_count,
            COUNT(DISTINCT c.id) as comment_count
        FROM posts p 
        JOIN users u ON p.user_id = u.id 
        LEFT JOIN likes l ON p.id = l.post_id
        LEFT JOIN comments c ON p.id = c.post_id
        GROUP BY p.id
        ORDER BY p.created_at DESC
    ";
    
    $result = $mysqli->query($query);
    
    if (!$result) {
        throw new Exception("Lỗi truy vấn: " . $mysqli->error);
    }
    
    $posts = [];
    while ($row = $result->fetch_assoc()) {
        // Kiểm tra user hiện tại đã like bài đăng này chưa
        $is_liked = false;
        if (isset($_SESSION['user_id'])) {
            $is_liked = isUserLikedPost($_SESSION['user_id'], $row['id']);
        }
        
        $posts[] = [
            'id' => $row['id'],
            'username' => $row['username'],
            'avatar' => $row['avatar'],
            'title' => $row['title'] ?? '',
            'content' => $row['content'],
            'location' => $row['location'],
            'latitude' => $row['latitude'],
            'longitude' => $row['longitude'],
            'image' => $row['image'],
            'feeling' => $row['feeling'],
            'likeCount' => intval($row['like_count']),
            'commentCount' => intval($row['comment_count']),
            'liked' => $is_liked,
            'createdAt' => $row['created_at']
        ];
    }
    
    echo json_encode([
        'success' => true, 
        'posts' => $posts,
        'total' => count($posts)
    ]);
    
} catch (Exception $e) {
    error_log("API Error: " . $e->getMessage());
    echo json_encode([
        'success' => false, 
        'message' => 'Lỗi khi lấy dữ liệu bài đăng: ' . $e->getMessage()
    ]);
}
?> 