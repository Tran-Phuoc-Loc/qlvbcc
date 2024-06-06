<?php
// Khai báo các lỗi PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'db.php';
require 'vendor/autoload.php'; // Đảm bảo đường dẫn đến autoload.php đúng

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $certificate_number = $_POST['certificate_number'];
    $student_name = $_POST['student_name'];
    $issuing_institution = $_POST['issuing_institution'];
    $address = $_POST['address'];
    $email = $_POST['email'];
        // Đường dẫn tới hình ảnh
        $imagePath = 'C:/xampp/htdocs/qlvbcc/image/cc.jpg';


    // Đọc nội dung tệp ảnh
    $imageData = file_get_contents($imagePath);

    // Chuyển đổi dữ liệu ảnh sang dạng nhị phân (binary)
    $imageData = base64_encode($imageData);

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

    // Thêm mã xác minh vào cơ sở dữ liệu cùng với thời gian tạo
    $stmt = $conn->prepare("INSERT INTO email_verification (email, verification_code, created_at) VALUES (:email, :verification_code, NOW())");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':verification_code', $verification_code);
    $stmt->execute();


    // Cập nhật bảng certificates với email và ảnh
    $stmt = $conn->prepare("UPDATE certificates SET email = :email, CertificatePicture = :imageData WHERE certificate_number = :certificate_number");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':imageData', $imageData);
    $stmt->bindParam(':certificate_number', $certificate_number);
    $stmt->execute();

    // Tạo đường dẫn xác minh
    $verification_link = "http://localhost/qlvbcc/verify.php?code=" . $verification_code . "&email=" . urlencode($email);

    // Chuẩn bị email
    $subject = "Xác minh địa chỉ email";
    $message = "Nhấn vào đường dẫn sau để xác minh địa chỉ email của bạn: " . $verification_link;

    // Sử dụng PHPMailer để gửi email
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'honkaiimpact968@gmail.com'; // Thay thế bằng email của bạn
        $mail->Password = 'honkai290722'; // Thay thế bằng mật khẩu email của bạn
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        //Recipients
        $mail->setFrom('honkaiimpact968@gmail.com', 'Mailer');
        $mail->addAddress($email);

        //Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
        echo 'Một email xác minh đã được gửi đến địa chỉ email của bạn.';

        // Chuyển hướng người dùng đến trang nhập mã xác minh
        header('Location: enter_code.php?email=' . urlencode($email));
        exit();
    } catch (Exception $e) {
        echo "Không thể gửi email xác minh. Vui lòng thử lại sau. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
