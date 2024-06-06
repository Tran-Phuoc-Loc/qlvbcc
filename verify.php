<?php
// Khai báo các lỗi PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'db.php';
session_start();

if (isset($_GET['code'])) {
    $verification_code = $_GET['code'];

    // Xóa các mã xác minh đã hết hạn (quá 5 phút)
    $stmt = $conn->prepare("DELETE FROM email_verification WHERE created_at < NOW() - INTERVAL 5 MINUTE");
    $stmt->execute();

    // Kiểm tra mã xác minh trong cơ sở dữ liệu
    $stmt = $conn->prepare("SELECT email FROM email_verification WHERE verification_code = :verification_code");
    $stmt->bindParam(':verification_code', $verification_code);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $email = $row['email'];

        // Lưu email và mã xác minh vào session để sử dụng sau
        $_SESSION['email'] = $email;
        $_SESSION['verification_code'] = $verification_code;

        // Chuyển hướng đến trang nhập mã xác minh
        header('Location: enter_code.php');
        exit();
    } else {
        echo "Mã xác minh không hợp lệ hoặc đã hết hạn.";
    }
} else {
    echo "Yêu cầu không hợp lệ.";
}
?>
