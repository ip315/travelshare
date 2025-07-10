<?php
require_once __DIR__ . '/../config/db.php';

$keyword = isset($_GET['q']) ? $mysqli->real_escape_string($_GET['q']) : '';

$suggestions = [];

if ($keyword) {
    // Gợi ý tiêu đề bài viết + địa điểm
    $sql = "SELECT id, title, location FROM posts WHERE title LIKE '%$keyword%' OR location LIKE '%$keyword%' LIMIT 5";
    $result = $mysqli->query($sql);
    while ($row = $result->fetch_assoc()) {
        $suggestions[] = [
            'type' => 'post',
            'id' => $row['id'], // Bổ sung trả về id
            'title' => $row['title'],
            'location' => $row['location']
        ];
    }

    // Gợi ý người dùng
    $sql2 = "SELECT id, username FROM users WHERE username LIKE '%$keyword%' LIMIT 5";
    $result2 = $mysqli->query($sql2);
    while ($row = $result2->fetch_assoc()) {
        $suggestions[] = [
            'type' => 'user',
            'username' => $row['username']
        ];
    }

    // Gợi ý địa điểm (không trùng tiêu đề)
    $sql3 = "SELECT DISTINCT location FROM posts WHERE location LIKE '%$keyword%' LIMIT 5";
    $result3 = $mysqli->query($sql3);
    while ($row = $result3->fetch_assoc()) {
        $suggestions[] = [
            'type' => 'location',
            'location' => $row['location']
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($suggestions); 