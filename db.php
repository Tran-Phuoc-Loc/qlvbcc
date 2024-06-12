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

// Truy vấn dữ liệu từ bảng gmail_accounts
$stmt = $conn->query("SELECT id, password FROM gmail_accounts");
$accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Duyệt qua từng dòng dữ liệu và cập nhật mật khẩu đã mã hóa
foreach ($accounts as $account) {
    $id = $account['id'];
    $password = $account['password'];

    // Mã hóa mật khẩu
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Cập nhật mật khẩu đã mã hóa vào bảng gmail_accounts
    $updateStmt = $conn->prepare("UPDATE gmail_accounts SET password = :password WHERE id = :id");
    $updateStmt->bindParam(':password', $hashedPassword);
    $updateStmt->bindParam(':id', $id);
    $updateStmt->execute();
}
