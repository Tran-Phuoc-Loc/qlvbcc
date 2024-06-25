<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin chứng chỉ</title>
</head>

<body>
    <?php
    if ($_POST) {
        $string = $_POST['phone'];
        $pattern = '#^?[\d]3?-?[\d]2?-[\d]{2}\.[\d]{3}-[\d]{3}$#';
        if (preg_match($pattern, $string, $match) == 1) {
            $report = '<span style=\'color:#298426\'>Bạn vừa nhập vào số điện thoại hợp lệ!</span>';
        } else {
            $report = '<span style=\'color:#FF0400\'>Bạn vừa nhập vào số điện thoại không hợp lệ!</span>';
        }
    }
    ?>
    <h1>Nhập Thông tin</h1>
    <form action="additional_info.php" method="POST" accept-charset="UTF-8">
        <label for="student_name">Họ và tên:</label>
        <input type="text" id="student_name" name="student_name" required> <br>
        <label for="issuing_institution">Đơn vị cấp chứng chỉ:</label>
        <input type="text" id="issuing_institution" name="issuing_institution" required> <br>
        <label for="address">Địa chỉ:</label>
        <input type="text" id="address" name="address" required> <br>
        <!-- <label for="email">Email:</label>
        <input type="email" id="email" name="email" required> <br> -->
        <label for="phone">Điện thoại</label>
        <input type="text" id="phone" name="phone" required> <br>
        <?php
        if (isset($report)) {
            echo $report;
        }
        ?>
        <button type="submit">Xác nhận</button>
    </form>
</body>

</html>
