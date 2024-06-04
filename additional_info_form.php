<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin chứng chỉ</title>
</head>
<body>
    <h1>Thông tin chứng chỉ</h1>
    <form action="verify_email.php" method="POST" accept-charset="UTF-8">
        <input type="hidden" name="certificate_number" value="<?php echo htmlspecialchars($_GET['certificate_number']); ?>">
        <label for="student_name">Họ và tên:</label>
        <input type="text" id="student_name" name="student_name" required> <br>
        <label for="issuing_institution">Đơn vị cấp chứng chỉ:</label>
        <input type="text" id="issuing_institution" name="issuing_institution" required> <br>
        <label for="address">Địa chỉ:</label>
        <input type="text" id="address" name="address" required> <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required> <br>
        <button type="submit">Xác nhận</button>
    </form>
</body>
</html>
