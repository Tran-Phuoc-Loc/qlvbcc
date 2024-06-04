<?php
// Khai báo các lỗi PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $certificate_number = $_POST['certificate_number'];
    $student_name = $_POST['student_name'];
    $issuing_institution = $_POST['issuing_institution'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    
    // Hàm tạo mã xác minh
function generateVerificationCode($length = 6) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $verificationCode = '';
    $max = strlen($characters) - 1;
    for ($i = 0; $i < $length; $i++) {
        $verificationCode .= $characters[mt_rand(0, $max)];
    }
    return $verificationCode;
}

// Tạo mã xác minh mới
$verification_code = generateVerificationCode();

// Thêm mã xác minh vào cơ sở dữ liệu
$stmt = $conn->prepare("INSERT INTO email_verification (email, verification_code) VALUES (:email, :verification_code)");
$stmt->bindParam(':email', $email);
$stmt->bindParam(':verification_code', $verification_code);
$stmt->execute();

    // Tạo mã xác minh
    $verification_code = md5(uniqid(mt_rand(), true));

    // Tạo đường dẫn xác minh
    $verification_link = "http://localhost/verify.php?code=" . $verification_code;

    // Chuẩn bị email
    $subject = "Xác minh địa chỉ email";
    $message = "Nhấn vào đường dẫn sau để xác minh địa chỉ email của bạn: " . $verification_link;
    $headers = "From: honkaiimpact968@gmail.com";

    // Gửi email
    if (mail($email, $subject, $message, $headers)) {
        // Lưu mã xác minh vào cơ sở dữ liệu
        $stmt = $conn->prepare("INSERT INTO email_verification (email, verification_code) VALUES (:email, :verification_code)");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':verification_code', $verification_code);
        $stmt->execute();

        echo "Một email xác minh đã được gửi đến địa chỉ email của bạn.";
    } else {
        echo "Không thể gửi email xác minh. Vui lòng thử lại sau.";
    }
}
?>
