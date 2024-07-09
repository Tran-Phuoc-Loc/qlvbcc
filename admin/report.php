<?php
// Bật hiển thị lỗi để dễ dàng debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../db.php'; // Điều chỉnh đường dẫn tới tệp db.php

// Đếm số lượng thông tin chứng chỉ
try {
    $count_stmt = $conn->query("SELECT COUNT(*) FROM information");
    $total_rows = $count_stmt->fetchColumn();
} catch (Exception $e) {
    echo '<span style="color:#FF0400">Lỗi khi đếm số lượng hàng: ' . $e->getMessage() . '</span>';
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Báo cáo Số lượng Thông tin Chứng chỉ</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #343a40;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #6c757d;
        }
    </style>
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand" href="index_admin.php">Quản lý Thông tin</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="admin.php">Quản lý Thông tin</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="report.php">Báo cáo Số lượng</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container mt-5">
        <h1 class="text-center">Báo cáo Số lượng Thông tin Chứng chỉ</h1>
        <p class="text-center">Tổng số lượng Thông tin Chứng chỉ: <?php echo $total_rows; ?></p>
        <div class="footer">
            &copy; <?php echo date("Y"); ?> Báo cáo Số lượng Thông tin Chứng chỉ. All rights reserved.
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>
