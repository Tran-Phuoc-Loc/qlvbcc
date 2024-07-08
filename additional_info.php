<?php

// Bật hiển thị lỗi để dễ dàng debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require_once 'vendor/autoload.php'; // Đường dẫn đến autoload.php được tạo bởi Composer
require_once 'db.php'; // Tệp kết nối cơ sở dữ liệu của bạn


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_name = $_POST['student_name'];
    $issuing_institution = $_POST['issuing_institution'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    // $email = isset($_POST['email']) ? $_POST['email'] : null;
    // $certificate_number = isset($_POST['certificate_number']) ? $_POST['certificate_number'] : null;
    // if ($email && $certificate_number) {
    //     $stmt = $conn->prepare("SELECT * FROM cartificates WHERE email = :email AND certificate_number = :certificate_number");
    //     $stmt->bindParam(':email', $email);
    //     $stmt->bindParam(':carteficate_number', $certificate_number);
    //     $stmt->execute();

    //     if($stmt->rowCount() > 0) {
    //         $row = $stmt->fetch(PDO::FETCH_ASSOC);
    //     } else {
    //         echo "Không tìm thấy số chứng chỉ này";
    //         exit;
    //     }

    // }

    // Lưu thông tin vào session
    $_SESSION['student_name'] = $student_name;
    $_SESSION['issuing_institution'] = $issuing_institution;
    $_SESSION['address'] = $address;
    $_SESSION['phone'] = $phone;
    $client = new Google_Client();
    $client->setClientId('199805261899-aavu0vckkke7f4mr6f0589mo392hbrp4.apps.googleusercontent.com');
    $client->setClientSecret('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');
    $client->setRedirectUri('http://localhost/qlvbcc/oauth2callback.php'); // Thay đổi thành URI chuyển hướng của bạn
    $client->addScope("email");
    $client->addScope("profile");

    // Chuyển hướng đến Google để xác thực
    $authUrl = $client->createAuthUrl();
    header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
    exit();
}

?>

// // Bật hiển thị lỗi để dễ dàng debug
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// // Bao gồm Google Client Library và kết nối đến cơ sở dữ liệu của bạn
// require_once 'vendor/autoload.php'; // Đường dẫn đến autoload.php được tạo bởi Composer
// require_once 'db.php'; // Tệp kết nối cơ sở dữ liệu của bạn

// // Xử lý yêu cầu POST để xử lý dữ liệu biểu mẫu
// if ($_SERVER["REQUEST_METHOD"] == "POST") {
// // Lấy mã thông tin Google ID từ dữ liệu biểu mẫu
// $google_token = $_POST['google_token'];

// // Khởi tạo Google Client với client ID của bạn
// $client = new Google_Client(['client_id' => '199805261899-5fbbuf08reqlemu3dvbgmlginse5navo.apps.googleusercontent.com']);

// try {
// // Xác minh mã thông tin ID
// $payload = $client->verifyIdToken($google_token);

// if ($payload) {
// // Trích xuất email và dữ liệu biểu mẫu khác
// $email = $payload['email'];
// $student_name = $_POST['student_name'];
// $issuing_institution = $_POST['issuing_institution'];
// $address = $_POST['address'];

// // Chuẩn bị câu lệnh SQL để chèn dữ liệu vào bảng 'information'
// $stmt = $conn->prepare("INSERT INTO information (student_name, issuing_institution, address, email) VALUES (:student_name, :issuing_institution, :address, :email)");
// $stmt->bindParam(':student_name', $student_name);
// $stmt->bindParam(':issuing_institution', $issuing_institution);
// $stmt->bindParam(':address', $address);
// $stmt->bindParam(':email', $email);
// $stmt->execute();

// // Chuyển hướng đến trang lookup_form với tham số email để hiển thị biểu mẫu tra cứu
// header("Location: lookup_form.php?email=" . urlencode($email));
// exit();
// } else {
// echo "Xác minh Google không thành công. Vui lòng thử lại.";
// }
// } catch (Exception $e) {
// echo "Lỗi xác minh Google: " . $e->getMessage();
// }
// }
?>



// if ($_SERVER["REQUEST_METHOD"] == "POST") {
// $student_name = $_POST['student_name'];
// $issuing_institution = $_POST['issuing_institution'];
// $address = $_POST['address'];
// $email = $_POST['email'];

// // Lưu thông tin vào cơ sở dữ liệu
// $stmt = $conn->prepare("INSERT INTO certificates (student_name, issuing_institution, address, email) VALUES (:student_name, :issuing_institution, :address, :email)");
// $stmt->bindParam(':student_name', $student_name);
// $stmt->bindParam(':issuing_institution', $issuing_institution);
// $stmt->bindParam(':address', $address);
// $stmt->bindParam(':email', $email);
// $stmt->execute();

// Chuyển hướng đến trang tra cứu chứng chỉ
// header("Location: additional_info_form.php?email=" . urlencode($email));
// exit(); // Dừng thực thi mã sau khi chuyển hướng
// }

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
// $certificate_number = $_POST['certificate_number'];

// if (!empty($certificate_number)) {
// header("Location: index.php?certificate_number=" . urlencode($certificate_number));
// exit();
// } else {
// echo "Vui lòng cung cấp mã số chứng chỉ.";
// }
// }
?>
