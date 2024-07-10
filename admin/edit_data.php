<?php
// Bật hiển thị lỗi để dễ dàng debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../db.php'; // Điều chỉnh đường dẫn tới tệp db.php

// Lấy dữ liệu cần sửa
if (isset($_GET['id'])) {
    $edit_id = $_GET['id'];
    try {
        $stmt = $conn->prepare("SELECT * FROM certificates WHERE id = ?");
        $stmt->execute([$edit_id]);
        $certificate = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $error_message = "Lỗi khi lấy dữ liệu: " . $e->getMessage();
    }
}

// Cập nhật dữ liệu
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $edit_id = $_POST['edit_id'];
    $certificate_number = $_POST['certificate_number'];
    $full_name = $_POST['full_name'];
    $birth_year = $_POST['birth_year'];
    $gender = $_POST['gender'];
    $training_course = $_POST['training_course'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $issue_date = $_POST['issue_date'];
    $email = $_POST['email'];
    $certificate_picture_path = $certificate['CertificatePicture'];

    // Kiểm tra nếu tệp được tải lên
    if (!empty($_FILES['certificate_picture']['tmp_name'])) {
        $target_dir = "image/";
        $target_file = $target_dir . basename($_FILES["certificate_picture"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Kiểm tra nếu tệp là ảnh hợp lệ
        $check = getimagesize($_FILES["certificate_picture"]["tmp_name"]);
        if($check !== false) {
            if (move_uploaded_file($_FILES["certificate_picture"]["tmp_name"], $target_file)) {
                // Xóa ảnh cũ nếu tồn tại
                if (file_exists($certificate_picture_path)) {
                    unlink($certificate_picture_path);
                }
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
        // Chuẩn bị câu lệnh SQL để cập nhật dữ liệu
        $stmt = $conn->prepare("UPDATE certificates SET certificate_number = ?, full_name = ?, birth_year = ?, gender = ?, training_course = ?, start_date = ?, end_date = ?, issue_date = ?, CertificatePicture = ?, email = ? WHERE id = ?");
        $stmt->execute([$certificate_number, $full_name, $birth_year, $gender, $training_course, $start_date, $end_date, $issue_date, $certificate_picture_path, $email, $edit_id]);

        if ($stmt) {
            $success_message = "Dữ liệu đã được cập nhật thành công!";
        } else {
            $error_message = "Lỗi khi cập nhật dữ liệu!";
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
    <title>Sửa Dữ liệu</title>
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
        <h1 class="text-center">Sửa Dữ liệu</h1>
        <?php
        if (!empty($success_message)) {
            echo '<div class="alert alert-success">' . $success_message . '</div>';
        }
        if (!empty($error_message)) {
            echo '<div class="alert alert-danger">' . $error_message . '</div>';
        }
        ?>
        <?php if (isset($certificate)): ?>
        <form method="post" action="" enctype="multipart/form-data">
            <input type="hidden" name="edit_id" value="<?php echo htmlspecialchars($certificate['id']); ?>">
            <div class="form-group">
                <label for="certificate_number">Số chứng chỉ</label>
                <input type="text" class="form-control" id="certificate_number" name="certificate_number" value="<?php echo htmlspecialchars($certificate['certificate_number']); ?>" required>
            </div>
            <div class="form-group">
                <label for="full_name">Họ và tên</label>
                <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo htmlspecialchars($certificate['full_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="birth_year">Năm sinh</label>
                <input type="number" class="form-control" id="birth_year" name="birth_year" value="<?php echo htmlspecialchars($certificate['birth_year']); ?>" required>
            </div>
            <div class="form-group">
                <label for="gender">Giới tính</label>
                <input type="text" class="form-control" id="gender" name="gender" value="<?php echo htmlspecialchars($certificate['gender']); ?>" required>
            </div>
            <div class="form-group">
                <label for="training_course">Khóa đào tạo</label>
                <input type="text" class="form-control" id="training_course" name="training_course" value="<?php echo htmlspecialchars($certificate['training_course']); ?>" required>
            </div>
            <div class="form-group">
                <label for="start_date">Ngày bắt đầu</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo htmlspecialchars($certificate['start_date']); ?>" required>
            </div>
            <div class="form-group">
                <label for="end_date">Ngày kết thúc</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo htmlspecialchars($certificate['end_date']); ?>" required>
            </div>
            <div class="form-group">
                <label for="issue_date">Ngày cấp</label>
                <input type="text" class="form-control" id="issue_date" name="issue_date" value="<?php echo htmlspecialchars($certificate['issue_date']); ?>" required>
            </div>
            <div class="form-group">
                <label for="certificate_picture">Ảnh chứng chỉ</label>
                <input type="file" class="form-control" id="certificate_picture" name="certificate_picture">
                <?php if (!empty($certificate['CertificatePicture'])): ?>
                    <img src="<?php echo htmlspecialchars($certificate['CertificatePicture']); ?>" alt="Certificate Picture" style="max-width: 200px; margin-top: 10px;">
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($certificate['email']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
        </form>
        <?php else: ?>
            <p class="text-center">Không tìm thấy dữ liệu cần chỉnh sửa.</p>
        <?php endif; ?>
        <div class="footer">
            &copy; <?php echo date("Y"); ?> Sửa Dữ liệu. All rights reserved.
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>
