<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require_once 'vendor/autoload.php';
require_once 'db.php';

use Google\Client as Google_Client;
use Google\Service\Oauth2 as Google_Service_Oauth2;

$client = new Google_Client();
$client->setClientId('199805261899-aavu0vckkke7f4mr6f0589mo392hbrp4.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/qlvbcc/oauth2callback.php');
$client->addScope("email");
$client->addScope("profile");

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    // Kiểm tra nếu $token là null hoặc có lỗi
    if (is_null($token) || isset($token['error'])) {
        echo "Xác minh Google không thành công. Vui lòng thử lại.<br>";
        var_dump($token); // In thông tin lỗi ra để dễ debug
        exit();
    }

    $client->setAccessToken($token['access_token']);

    // Kiểm tra nếu access token là null
    if (is_null($client->getAccessToken())) {
        echo "Không lấy được access token. Vui lòng thử lại.<br>";
        var_dump($client->getAccessToken()); // In access token để debug
        exit();
    }

    $oauth = new Google_Service_Oauth2($client);
    $userInfo = $oauth->userinfo->get();

    // Kiểm tra nếu $userInfo là null hoặc không chứa email
    if (is_null($userInfo) || empty($userInfo->email)) {
        echo "Không lấy được thông tin người dùng. Vui lòng thử lại.<br>";
        var_dump($userInfo); // In thông tin người dùng để debug
        exit();
    }

    $email = $userInfo->email;
    $student_name = $_SESSION['student_name'] ?? 'Unknown'; // Gán giá trị mặc định nếu không có trong session
    $issuing_institution = $_SESSION['issuing_institution'] ?? 'Unknown'; // Gán giá trị mặc định nếu không có trong session
    $address = $_SESSION['address'] ?? 'Unknown'; // Gán giá trị mặc định nếu không có trong session
    $phone = $_SESSION['phone'] ?? 'Unknown'; // Gán giá trị mặc định nếu không có trong session

    try {
        $stmt = $conn->prepare("INSERT INTO information (student_name, issuing_institution, address, phone, email) VALUES (:student_name, :issuing_institution, :address, :phone, :email)");
        $stmt->bindParam(':student_name', $student_name);
        $stmt->bindParam(':issuing_institution', $issuing_institution);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
    } catch (Exception $e) {
        echo "Lỗi khi lưu thông tin vào cơ sở dữ liệu: " . $e->getMessage();
        exit();
    }

    unset($_SESSION['student_name']);
    unset($_SESSION['issuing_institution']);
    unset($_SESSION['address']);
    unset($_SESSION['phone']);

    $url = "additional_info_form.php?email=" . urlencode($email);
    echo "Redirecting to: " . $url; // Hiển thị URL trước khi chuyển hướng
    header("Location: " . $url);
    exit();
} else {
    echo "Xác minh Google không thành công. Vui lòng thử lại.<br>";
    var_dump($_GET); // In nội dung của $_GET để debug
}
?>
