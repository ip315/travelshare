<?php
require_once __DIR__ . '/../config/db.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Bạn chưa đăng nhập']);
    exit;
}

$title = $_POST['title'] ?? '';
$content = $_POST['content'] ?? '';
$location = $_POST['location'] ?? '';
$feeling = $_POST['feeling'] ?? '';
$latitude = isset($_POST['latitude']) && $_POST['latitude'] !== '' ? floatval($_POST['latitude']) : null;
$longitude = isset($_POST['longitude']) && $_POST['longitude'] !== '' ? floatval($_POST['longitude']) : null;
$user_id = $_SESSION['user_id'];
$image_name = null;

// Xử lý upload ảnh
if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $target_dir = __DIR__ . '/../assets/uploads/';
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $image_name = uniqid() . '_' . basename($_FILES['image']['name']);
    $target_file = $target_dir . $image_name;
    move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
}

// Thêm bài viết vào database
$stmt = $mysqli->prepare("INSERT INTO posts (user_id, title, location, latitude, longitude, content, image, feeling) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("issddsss", $user_id, $title, $location, $latitude, $longitude, $content, $image_name, $feeling);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Lỗi khi lưu bài viết']);
}
$stmt->close();
?> 