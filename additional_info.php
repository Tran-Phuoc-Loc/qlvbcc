<?php

// Bật hiển thị lỗi để dễ dàng debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require_once 'vendor/autoload.php'; // Đường dẫn đến autoload.php 
require_once 'db.php'; // Tệp kết nối cơ sở dữ liệu của bạn


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_name = $_POST['student_name'];
    $issuing_institution = $_POST['issuing_institution'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

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


