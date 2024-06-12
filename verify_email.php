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

    // // Kiểm tra xem email có được liên kết với chứng chỉ khác không
    // $stmt = $conn->prepare("SELECT * FROM certificates WHERE email = :email");
    // $stmt->bindParam(':email', $email);
    // $stmt->execute();

    // if ($stmt->rowCount() > 0) {
    //     echo "Email này đã được liên kết với chứng chỉ khác. Vui lòng sử dụng email khác.";
    //     exit();
    // }

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

    // Truy vấn một tài khoản Gmail đã đăng ký
    $stmt = $conn->prepare("SELECT * FROM gmail_accounts LIMIT 1");
    $stmt->execute();
    $gmailAccount = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($gmailAccount) {
        $gmailEmail = $gmailAccount['email'];
        $gmailPassword = $gmailAccount['password'];

        // Tạo đường dẫn xác minh
        $verification_link = "http://localhost/qlvbcc/verify.php?code=" . $verification_code . "&email=" . urlencode($email);

        // Chuẩn bị email
        $subject = "Xác minh địa chỉ email";
        $message = "Nhấn vào đường dẫn sau để xác minh địa chỉ email của bạn: " . $verification_link;

        // Sử dụng PHPMailer để gửi email
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        try {
            //Cài đặt Server
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $gmailEmail; // Sử dụng email được lấy từ cơ sở dữ liệu
            $mail->Password = $gmailPassword; // Sử dụng mật khẩu được lấy từ cơ sở dữ liệu
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            //Người nhận
            $mail->setFrom($gmailEmail, 'Mailer');
            $mail->addAddress($email);

            //Nội dung
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
    } else {
        echo "Không tìm thấy tài khoản Gmail để gửi email.";
    }
}
?>
