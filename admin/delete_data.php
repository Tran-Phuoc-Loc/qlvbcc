<?php
// Bật hiển thị lỗi để dễ dàng debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../db.php'; // Điều chỉnh đường dẫn tới tệp db.php

// Xóa dữ liệu
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    try {
        // Lấy đường dẫn ảnh trước khi xóa
        $stmt = $conn->prepare("SELECT CertificatePicture FROM certificates WHERE id = ?");
        $stmt->execute([$delete_id]);
        $certificate = $stmt->fetch(PDO::FETCH_ASSOC);

        // Xóa dữ liệu khỏi cơ sở dữ liệu
        $stmt = $conn->prepare("DELETE FROM certificates WHERE id = ?");
        $stmt->execute([$delete_id]);

        // Xóa ảnh nếu tồn tại
        if ($certificate && !empty($certificate['CertificatePicture']) && file_exists($certificate['CertificatePicture'])) {
            unlink($certificate['CertificatePicture']);
        }

        $success_message = "Dữ liệu đã được xóa thành công!";
    } catch (Exception $e) {
        $error_message = "Lỗi khi xóa dữ liệu: " . $e->getMessage();
    }
}

// Lấy dữ liệu từ cơ sở dữ liệu
try {
    $stmt = $conn->query("SELECT * FROM certificates");
    $certificates = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error_message = "Lỗi khi lấy dữ liệu: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xóa Dữ liệu từ Cơ sở Dữ liệu</title>
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
        <h1 class="text-center">Xóa Thông Tin</h1>
        <?php
        if (!empty($success_message)) {
            echo '<div class="alert alert-success">' . $success_message . '</div>';
        }
        if (!empty($error_message)) {
            echo '<div class="alert alert-danger">' . $error_message . '</div>';
        }
        ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Số chứng chỉ</th>
                    <th>Họ và tên</th>
                    <th>Năm sinh</th>
                    <th>Giới tính</th>
                    <th>Khóa đào tạo</th>
                    <th>Ngày bắt đầu</th>
                    <th>Ngày kết thúc</th>
                    <th>Ngày cấp</th>
                    <th>Email</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($certificates)) : ?>
                    <?php foreach ($certificates as $certificate) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($certificate['id']); ?></td>
                            <td><?php echo htmlspecialchars($certificate['certificate_number']); ?></td>
                            <td><?php echo htmlspecialchars($certificate['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($certificate['birth_year']); ?></td>
                            <td><?php echo htmlspecialchars($certificate['gender']); ?></td>
                            <td><?php echo htmlspecialchars($certificate['training_course']); ?></td>
                            <td><?php echo htmlspecialchars($certificate['start_date']); ?></td>
                            <td><?php echo htmlspecialchars($certificate['end_date']); ?></td>
                            <td><?php echo htmlspecialchars($certificate['issue_date']); ?></td>
                            <td><?php echo htmlspecialchars($certificate['email']); ?></td>
                            <td>
                                <form method="post" action="" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');" style="display:inline-block;">
                                    <input type="hidden" name="delete_id" value="<?php echo $certificate['id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                                </form>
                                <a href="edit_data.php?id=<?php echo $certificate['id']; ?>" class="btn btn-warning btn-sm">Sửa</a>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="11" class="text-center">Không có dữ liệu</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="footer">
            &copy; <?php echo date("Y"); ?> Xóa Dữ liệu. All rights reserved.
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>