<?php
// Cấu hình kết nối cơ sở dữ liệu
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'travelshare_db');

// Tạo kết nối database với error handling tốt hơn
function getDBConnection() {
    static $mysqli = null;
    
    if ($mysqli === null) {
        try {
            $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
            
            // Kiểm tra kết nối
            if ($mysqli->connect_error) {
                throw new Exception("LỖI: Không thể kết nối database. " . $mysqli->connect_error);
            }
            
            // Thiết lập charset
            if (!$mysqli->set_charset("utf8mb4")) {
                throw new Exception("LỖI: Không thể thiết lập charset. " . $mysqli->error);
            }
            
            // Thiết lập timezone
            $mysqli->query("SET time_zone = '+07:00'");
            
        } catch (Exception $e) {
            error_log("Database connection error: " . $e->getMessage());
            die("Lỗi kết nối database: " . $e->getMessage());
        }
    }
    
    return $mysqli;
}

// Tạo kết nối mặc định
$mysqli = getDBConnection();

// Hàm helper để đếm số lượng thích
function getLikeCount($post_id) {
    global $mysqli;
    $post_id = intval($post_id);
    $result = $mysqli->query("SELECT COUNT(*) as total FROM likes WHERE post_id = $post_id");
    if ($result) {
        $row = $result->fetch_assoc();
        return $row['total'];
    }
    return 0;
}

// Hàm helper để đếm số lượng bình luận
function getCommentCount($post_id) {
    global $mysqli;
    $post_id = intval($post_id);
    $result = $mysqli->query("SELECT COUNT(*) as total FROM comments WHERE post_id = $post_id");
    if ($result) {
        $row = $result->fetch_assoc();
        return $row['total'];
    }
    return 0;
}

// Hàm helper để kiểm tra user đã like post chưa
function isUserLikedPost($user_id, $post_id) {
    global $mysqli;
    $user_id = intval($user_id);
    $post_id = intval($post_id);
    $result = $mysqli->query("SELECT COUNT(*) as total FROM likes WHERE user_id = $user_id AND post_id = $post_id");
    if ($result) {
        $row = $result->fetch_assoc();
        return $row['total'] > 0;
    }
    return false;
}
?>