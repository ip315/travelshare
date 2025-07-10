<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/db.php';
header('Content-Type: application/json');
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Chỉ hỗ trợ POST']);
    exit();
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Bạn chưa đăng nhập!']);
    exit();
}

$user_id = $_SESSION['user_id'];
$title = $_POST['title'] ?? '';
$content = $_POST['content'] ?? '';
$location = $_POST['location'] ?? null;
$feeling = $_POST['feeling'] ?? null;
$image = null;

// Xử lý upload ảnh (nếu có)
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = __DIR__ . '/../assets/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    $fileTmp = $_FILES['image']['tmp_name'];
    $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
    $filePath = $uploadDir . $fileName;
    if (move_uploaded_file($fileTmp, $filePath)) {
        $image = $fileName;
    }
}

if (empty($title) || empty($content)) {
    echo json_encode(['success' => false, 'message' => 'Tiêu đề và nội dung không được để trống!']);
    exit();
}

// Kiểm tra user_id có tồn tại không
$stmtUser = $mysqli->prepare("SELECT id FROM users WHERE id = ?");
$stmtUser->bind_param("i", $user_id);
$stmtUser->execute();
$stmtUser->store_result();
if ($stmtUser->num_rows === 0) {
    $stmtUser->close();
    echo json_encode(['success' => false, 'message' => 'Tài khoản không hợp lệ!']);
    exit();
}
$stmtUser->close();

// Thêm bài đăng
$stmt = $mysqli->prepare("INSERT INTO posts (user_id, title, content, location, feeling, image) VALUES (?, ?, ?, ?, ?, ?)");
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Lỗi prepare: ' . $mysqli->error]);
    exit();
}
$stmt->bind_param("isssss", $user_id, $title, $content, $location, $feeling, $image);
$success = $stmt->execute();
if (!$success) {
    $errorMsg = $stmt->error;
    $stmt->close();
    echo json_encode(['success' => false, 'message' => 'Lỗi khi lưu bài đăng: ' . $errorMsg]);
    exit();
}
$stmt->close();

echo json_encode(['success' => true, 'message' => 'Đăng bài thành công!']); 