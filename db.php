<?php
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "cc";
$port = "3306";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Lỗi kết nối: " . $e->getMessage();
    die();  // Dừng thực thi nếu không thể kết nối
}

