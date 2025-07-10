<?php
// Cấu hình kết nối cơ sở dữ liệu
 define('DB_SERVER', 'localhost');
 define('DB_USERNAME', 'root');
 define('DB_PASSWORD', '');
 define('DB_NAME', 'travelshare_db');

// // Cố gắng kết nối đến MySQL database
 // $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
 // Kiểm tra kết nối
 if($mysqli->connect_error){
    die("LỖI: Không thể kết nối. " . $mysqli->connect_error);
  }
  $mysqli->set_charset("utf8mb4");
?>