<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin chứng chỉ</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <?php
        $report = "";
        $student_name = "";
        $issuing_institution = "";
        $address = "";
        $phone = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $student_name = $_POST['student_name'];
            $issuing_institution = $_POST['issuing_institution'];
            $address = $_POST['address'];
            $phone = $_POST['phone'];

            // Biểu thức chính quy cho các trường
            $phone_pattern = '/^(0|\+84)\d{9}$/';


            if (!preg_match($phone_pattern, $phone)) {
                $report .= '<span class="error">Bạn vừa nhập vào số điện thoại không hợp lệ!</span><br>';
            }

            if ($report === "") {
                $report = '<span class="success">Tất cả thông tin đều hợp lệ!</span>';
            }
        }
        ?>
        <h1>Nhập Thông tin</h1>
        <form action="additional_info.php" method="POST" accept-charset="UTF-8">
            <div class="form-group">
                <label for="student_name">Họ và tên:</label>
                <input type="text" id="student_name" name="student_name" value="<?php echo htmlspecialchars($student_name); ?>" required>
            </div>
            <div class="form-group">
                <label for="issuing_institution">Đơn vị cấp chứng chỉ:</label>
                <input type="text" id="issuing_institution" name="issuing_institution" value="<?php echo htmlspecialchars($issuing_institution); ?>" required>
            </div>
            <div class="form-group">
                <label for="address">Địa chỉ:</label>
                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($address); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">Điện thoại:</label>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required>
            </div>
            <?php
            if ($report) {
                echo $report;
            }
            ?>
            <button type="submit">Xác nhận</button>
        </form>
    </div>
</body>

</html>
