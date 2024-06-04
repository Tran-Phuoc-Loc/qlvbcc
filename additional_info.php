<?php
// Khai báo các lỗi PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $certificate_number = $_POST['certificate_number'];

    if (!empty($certificate_number)) {
        header("Location: additional_info_form.php?certificate_number=" . urlencode($certificate_number));
        exit();
    } else {
        echo "Vui lòng cung cấp mã số chứng chỉ.";
    }
}
?>
