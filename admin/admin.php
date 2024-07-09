<?php
// Bật hiển thị lỗi để dễ dàng debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require_once '../db.php'; // Điều chỉnh đường dẫn tới tệp db.php

// Số lượng hàng mỗi trang
$rows_per_page = 15;

// Tính số trang hiện tại
$current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$start_from = ($current_page - 1) * $rows_per_page;

// Lấy thông tin từ bảng information với phân trang
try {
    $stmt = $conn->prepare("SELECT * FROM information LIMIT :start_from, :rows_per_page");
    $stmt->bindParam(':start_from', $start_from, PDO::PARAM_INT);
    $stmt->bindParam(':rows_per_page', $rows_per_page, PDO::PARAM_INT);
    $stmt->execute();
    $information = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo '<span style="color:#FF0400">Lỗi khi truy vấn cơ sở dữ liệu: ' . $e->getMessage() . '</span>';
    exit();
}

// Đếm số lượng hàng trong bảng
try {
    $count_stmt = $conn->query("SELECT COUNT(*) FROM information");
    $total_rows = $count_stmt->fetchColumn();
    $total_pages = ceil($total_rows / $rows_per_page);
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
    <title>Quản lý Thông tin Chứng chỉ</title>
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

        table {
            margin-top: 20px;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        td {
            color: #343a40;
        }

        .pagination {
            margin-top: 20px;
        }

        .pagination a {
            color: #007bff;
            text-decoration: none;
            margin: 0 5px;
        }

        .pagination .active a {
            color: white;
            background-color: #007bff;
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
        <h1 class="text-center">Quản lý Thông tin Chứng chỉ</h1>
        <table class="table table-bordered table-hover mt-4">
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
                <?php foreach ($information as $info) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($info['student_name']); ?></td>
                        <td><?php echo htmlspecialchars($info['issuing_institution']); ?></td>
                        <td><?php echo htmlspecialchars($info['address']); ?></td>
                        <td><?php echo htmlspecialchars($info['phone'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($info['email']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Phân trang -->
        <div class="pagination justify-content-center">
            <?php if ($current_page > 1) : ?>
                <a href="?page=<?php echo ($current_page - 1); ?>" class="page-link">&laquo; Trang trước</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                <a href="?page=<?php echo $i; ?>" class="page-link <?php echo ($i == $current_page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>

            <?php if ($current_page < $total_pages) : ?>
                <a href="?page=<?php echo ($current_page + 1); ?>" class="page-link">Trang sau &raquo;</a>
            <?php endif; ?>
        </div>

        <div class="footer">
            &copy; <?php echo date("Y"); ?> Quản lý Thông tin Chứng chỉ. All rights reserved.
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>