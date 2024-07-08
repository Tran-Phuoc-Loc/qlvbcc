<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nhập mã xác minh</title>
</head>
<body>
    <h1>Nhập mã xác minh</h1>
    <form action="lookup.php" method="POST">
        <label for="verification_code">Mã xác minh:</label>
        <input type="text" id="verification_code" name="verification_code" required> <br>
        <input type="hidden" name="email" value="<?php echo htmlspecialchars($_GET['email']); ?>">
        <button type="submit">Xác minh</button>
    </form>
</body>
</html>
<?php
$stmt-$conn->papare(select information from student_name, email
