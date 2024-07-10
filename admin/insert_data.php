<?php
// Bật hiển thị lỗi để dễ dàng debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../db.php'; // Điều chỉnh đường dẫn tới tệp db.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $certificate_number = $_POST['certificate_number'];
    $full_name = $_POST['full_name'];
    $birth_year = $_POST['birth_year'];
    $gender = $_POST['gender'];
    $training_course = $_POST['training_course'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $issue_date = $_POST['issue_date'];
    $email = $_POST['email'];
    $certificate_picture_path = null;

    // Kiểm tra nếu tệp được tải lên
    if (!empty($_FILES['certificate_picture']['tmp_name'])) {
        $target_dir = "image/";
        $target_file = $target_dir . basename($_FILES["certificate_picture"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Kiểm tra nếu tệp là ảnh hợp lệ
        $check = getimagesize($_FILES["certificate_picture"]["tmp_name"]);
        if($check !== false) {
            if (move_uploaded_file($_FILES["certificate_picture"]["tmp_name"], $target_file)) {
                $certificate_picture_path = $target_file;
            } else {
                $error_message = "Lỗi khi tải lên ảnh.";
            }
        } else {
            $error_message = "Tệp không phải là ảnh.";
        }
    }

    // Kiểm tra dữ liệu nhập vào
    if (!empty($certificate_number) && !empty($full_name) && !empty($birth_year) && !empty($gender) && !empty($training_course) && !empty($start_date) && !empty($end_date) && !empty($issue_date) && !empty($email)) {
        // Chuẩn bị câu lệnh SQL để chèn dữ liệu
        $stmt = $conn->prepare("INSERT INTO certificates (certificate_number, full_name, birth_year, gender, training_course, start_date, end_date, issue_date, CertificatePicture, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$certificate_number, $full_name, $birth_year, $gender, $training_course, $start_date, $end_date, $issue_date, $certificate_picture_path, $email]);

        if ($stmt) {
            $success_message = "Dữ liệu đã được chèn thành công!";
        } else {
            $error_message = "Lỗi khi chèn dữ liệu!";
        }
    } else {
        $error_message = "Vui lòng điền đầy đủ thông tin!";
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
        <h1 class="text-center">Thêm Dữ liệu vào Cơ sở Dữ liệu</h1>
        <?php
        if (!empty($success_message)) {
            echo '<div class="alert alert-success">' . $success_message . '</div>';
        }
        if (!empty($error_message)) {
            echo '<div class="alert alert-danger">' . $error_message . '</div>';
        }
        ?>
        <form method="post" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="certificate_number">Số chứng chỉ</label>
                <input type="text" class="form-control" id="certificate_number" name="certificate_number" required>
            </div>
            <div class="form-group">
                <label for="full_name">Họ và tên</label>
                <input type="text" class="form-control" id="full_name" name="full_name" required>
            </div>
            <div class="form-group">
                <label for="birth_year">Năm sinh</label>
                <input type="number" class="form-control" id="birth_year" name="birth_year" required>
            </div>
            <div class="form-group">
                <label for="gender">Giới tính</label>
                <input type="text" class="form-control" id="gender" name="gender" required>
            </div>
            <div class="form-group">
                <label for="training_course">Khóa đào tạo</label>
                <input type="text" class="form-control" id="training_course" name="training_course" required>
            </div>
            <div class="form-group">
                <label for="start_date">Ngày bắt đầu</label>
                <input type="date" class="form-control" id="start_date" name="start_date" required>
            </div>
            <div class="form-group">
                <label for="end_date">Ngày kết thúc</label>
                <input type="date" class="form-control" id="end_date" name="end_date" required>
            </div>
            <div class="form-group">
                <label for="issue_date">Ngày cấp</label>
                <input type="text" class="form-control" id="issue_date" name="issue_date" required>
            </div>
            <div class="form-group">
                <label for="certificate_picture">Ảnh chứng chỉ</label>
                <input type="file" class="form-control" id="certificate_picture" name="certificate_picture">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <button type="submit" class="btn btn-primary">Thêm dữ liệu</button>
        </form>
        <div class="footer">
            &copy; <?php echo date("Y"); ?> Thêm Dữ liệu vào Cơ sở Dữ liệu. All rights reserved.
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>
