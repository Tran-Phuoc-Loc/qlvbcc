<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require_once 'db.php';

if (isset($_GET['code'])) {
    $verification_code = $_GET['code'];

    $stmt = $conn->prepare("SELECT email FROM email_verification WHERE verification_code = :verification_code");
    $stmt->bindParam(':verification_code', $verification_code);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $email = $row['email'];

        // Xóa mã xác minh khỏi cơ sở dữ liệu sau khi xác minh thành công
        $stmt = $conn->prepare("DELETE FROM email_verification WHERE verification_code = :verification_code");
        $stmt->bindParam(':verification_code', $verification_code);
        $stmt->execute();

        // Hiển thị thông tin chứng chỉ
        $stmt = $conn->prepare("SELECT * FROM certificates WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            echo '<div style="text-align: center;">';
            echo "<h1>Thông tin chứng chỉ</h1>";
            echo "Mã số chứng chỉ: " . htmlspecialchars($row['certificate_number']) . "<br>";
            echo "Họ tên: " . htmlspecialchars($row['full_name']) . "<br>";
            echo "Năm sinh: " . htmlspecialchars($row['birth_year']) . "<br>";
            echo "Giới tính: " . htmlspecialchars($row['gender']) . "<br>";
            echo "Nghề đào tạo: " . htmlspecialchars($row['training_course']) . "<br>";
            echo "Thời gian học: " . htmlspecialchars($row['start_date']) . " đến " . htmlspecialchars($row['end_date']) . "<br>";
            echo "Ngày cấp: " . htmlspecialchars($row['issue_date']) . "<br>";

            echo '<form action="lookup.php" method="POST">';
                echo '<input type="hidden" name="certificate_number" value="' . htmlspecialchars($row['certificate_number']) . '">';
                echo '<button type="submit" name="create_copy" value="1">Tạo bản sao chứng chỉ</button>';
                echo '</form>';
                echo '</div>';

                if (isset($_POST['create_copy'])) {
                    echo '<div style="text-align: center; margin-top: 20px;">';
                    echo "<h2>Ảnh toàn bộ chứng chỉ</h2>";
                    echo "<img src='data:image/jpeg;base64," . base64_encode($row['CertificatePicture']) . "' width='800'/>";
                    echo '</div>';
                }
        } else {
            echo "Không tìm thấy chứng chỉ với email này.";
        }
    } else {
        echo "Mã xác minh không hợp lệ.";
    }
}
?>

