<?php
// Bật hiển thị lỗi để dễ dàng debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../db.php'; // Điều chỉnh đường dẫn tới tệp db.php

// Xử lý khi nhấn nút Thêm
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $certificate_number = $_POST['certificate_number'];
    $full_name = $_POST['full_name'];
    $birth_year = $_POST['birth_year'];
    $gender = $_POST['gender'];
    $training_course = $_POST['training_course'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $issue_date = $_POST['issue_date'];
    $email = $_POST['email'];

    // Thêm dữ liệu vào cơ sở dữ liệu
    try {
        $stmt = $conn->prepare("INSERT INTO certificates (certificate_number, full_name, birth_year, gender, training_course, start_date, end_date, issue_date, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$certificate_number, $full_name, $birth_year, $gender, $training_course, $start_date, $end_date, $issue_date, $email]);
        $success_message = "Dữ liệu đã được thêm thành công!";
    } catch (Exception $e) {
        $error_message = "Lỗi khi thêm dữ liệu: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chèn Dữ liệu vào Cơ sở Dữ liệu</title>
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
        <h1 class="text-center">Chèn Dữ liệu vào Cơ sở Dữ liệu</h1>
        <?php
        if (!empty($success_message)) {
            echo '<div class="alert alert-success">' . $success_message . '</div>';
        }
        if (!empty($error_message)) {
            echo '<div class="alert alert-danger">' . $error_message . '</div>';
        }
        ?>
        <form method="post">
            <div class="form-group">
                <label>Số chứng chỉ:</label>
                <input type="text" name="certificate_number" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Họ và tên:</label>
                <input type="text" name="full_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Năm sinh:</label>
                <input type="text" name="birth_year" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Giới tính:</label>
                <select name="gender" class="form-control" required>
                    <option value="Nam">Nam</option>
                    <option value="Nữ">Nữ</option>
                </select>
            </div>
            <div class="form-group">
                <label>Khóa đào tạo:</label>
                <input type="text" name="training_course" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Ngày bắt đầu:</label>
                <input type="date" name="start_date" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Ngày kết thúc:</label>
                <input type="date" name="end_date" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Ngày cấp:</label>
                <input type="text" name="issue_date" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Thêm</button>
        </form>
        <div class="footer">
            &copy; <?php echo date("Y"); ?> Chèn Dữ liệu vào Cơ sở Dữ liệu. All rights reserved.
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>
