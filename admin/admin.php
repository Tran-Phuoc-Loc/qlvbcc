<?php
// Bật hiển thị lỗi để dễ dàng debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require_once '../db.php'; // Điều chỉnh đường dẫn tới tệp db.php

// Lấy tất cả thông tin từ bảng information
try {
    $stmt = $conn->prepare("SELECT * FROM information");
    $stmt->execute();
    $information = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo '<span style="color:#FF0400">Lỗi khi truy vấn cơ sở dữ liệu: ' . $e->getMessage() . '</span>';
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Thông tin Chứng chỉ</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center">Quản lý Thông tin Chứng chỉ</h1>
        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>Họ và tên</th>
                    <th>Đơn vị cấp chứng chỉ</th>
                    <th>Địa chỉ</th>
                    <th>Điện thoại</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($information as $info): ?>
                <tr>
                    <td><?php echo htmlspecialchars($info['student_name']); ?></td>
                    <td><?php echo htmlspecialchars($info['issuing_institution']); ?></td>
                    <td><?php echo htmlspecialchars($info['address']); ?></td>
                    <td><?php echo htmlspecialchars($info['phone'] ?? ''); ?></td> <!-- Kiểm tra giá trị phone -->
                    <td><?php echo htmlspecialchars($info['email']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>
