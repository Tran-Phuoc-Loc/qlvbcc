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

    // Lấy thông tin chứng chỉ và đếm số lượng email, tên và số điện thoại giống nhau
    $info_stmt = $conn->query("SELECT student_name, email, phone, COUNT(*) as count FROM information GROUP BY student_name, email, phone ORDER BY student_name, email, phone");
    $infos = $info_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo '<span style="color:#FF0400">Lỗi khi lấy dữ liệu: ' . $e->getMessage() . '</span>';
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
        th {
            cursor: pointer;
        }
        th.sortable:after {
            content: " \25B2\25BC"; /* Thêm mũi tên xuống và mũi tên lên để biểu thị khả năng sắp xếp */
            font-size: 0.6em;
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
                <li class="nav-item">
                    <a class="nav-link" href="insert_data.php">Chèn Dữ liệu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="delete_data.php">Xóa Dữ liệu</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container mt-5">
        <h1 class="text-center">Báo cáo Số lượng Thông tin Truy Cập Chứng chỉ</h1>
        <p class="text-center">Tổng số lượng Thông tin Chứng chỉ: <?php echo $total_rows; ?></p>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th class="sortable" onclick="sortTable(0)">Tên sinh viên</th>
                    <th class="sortable" onclick="sortTable(1)">Email</th>
                    <th class="sortable" onclick="sortTable(2)">Số điện thoại</th>
                    <th class="sortable" onclick="sortTable(3)">Số lượng</th>
                </tr>
            </thead>
            <tbody id="infoTable">
                <?php
                foreach ($infos as $info) {
                    echo "<tr>
                        <td>{$info['student_name']}</td>
                        <td>{$info['email']}</td>
                        <td>{$info['phone']}</td>
                        <td>{$info['count']}</td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
        <div class="footer">
            &copy; <?php echo date("Y"); ?> Báo cáo Số lượng Thông tin Chứng chỉ. All rights reserved.
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script>
        function sortTable(n) {
            var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
            table = document.getElementById("infoTable");
            switching = true;
            dir = "asc";
            while (switching) {
                switching = false;
                rows = table.rows;
                for (i = 0; i < (rows.length - 1); i++) {
                    shouldSwitch = false;
                    x = rows[i].getElementsByTagName("TD")[n];
                    y = rows[i + 1].getElementsByTagName("TD")[n];
                    if (dir == "asc") {
                        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    } else if (dir == "desc") {
                        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                }
                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                    switchcount++;
                } else {
                    if (switchcount == 0 && dir == "asc") {
                        dir = "desc";
                        switching = true;
                    }
                }
            }
        }
    </script>
</body>

</html>
