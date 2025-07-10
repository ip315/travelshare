<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/db.php';
header('Content-Type: application/json');

$result = $mysqli->query("SELECT p.*, u.username, u.avatar FROM posts p JOIN users u ON p.user_id = u.id ORDER BY p.created_at DESC");
$posts = [];
while ($row = $result->fetch_assoc()) {
    $posts[] = [
        'id' => $row['id'],
        'username' => $row['username'],
        'avatar' => $row['avatar'],
        'title' => $row['title'] ?? '',
        'content' => $row['content'],
        'location' => $row['location'],
        'image' => $row['image'],
        'createdAt' => $row['created_at']
    ];
}
echo json_encode(['success' => true, 'posts' => $posts]); 