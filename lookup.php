<?php
// Khai báo các lỗi PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $verification_code = isset($_POST['verification_code']) ? $_POST['verification_code'] : null;
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $create_copy = isset($_POST['create_copy']) ? $_POST['create_copy'] : null;

    if ($verification_code && $email) {
        // In ra giá trị để kiểm tra
        // echo "Verification Code: " . htmlspecialchars($verification_code) . "<br>";
        // echo "Email: " . htmlspecialchars($email) . "<br>";

        // Kiểm tra mã xác minh
        $stmt = $conn->prepare("SELECT * FROM email_verification WHERE email = :email AND verification_code = :verification_code");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':verification_code', $verification_code);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Xóa mã xác minh sau khi sử dụng
            // $stmt = $conn->prepare("DELETE FROM email_verification WHERE verification_code = :verification_code");
            // $stmt->bindParam(':verification_code', $verification_code);
            // $stmt->execute();

            // Xóa các mã xác minh đã quá 5 phút
            $stmt = $conn->prepare("DELETE FROM email_verification WHERE created_at < NOW() - INTERVAL 5 MINUTE");
            $stmt->execute();

            // Hiển thị thông tin chứng chỉ
            $stmt = $conn->prepare("SELECT * FROM certificates WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                // Mã xác minh hợp lệ
                // echo "Mã xác minh hợp lệ. Bạn có thể xem thông tin chứng chỉ.";

                echo '<div style="text-align: center;">';
                echo "<h1>Thông tin chứng chỉ</h1>";
                echo "Mã số chứng chỉ: " . htmlspecialchars($row['certificate_number']) . "<br>";
                echo "Họ tên: " . htmlspecialchars($row['full_name']) . "<br>";
                echo "Năm sinh: " . htmlspecialchars($row['birth_year']) . "<br>";
                echo "Giới tính: " . htmlspecialchars($row['gender']) . "<br>";
                echo "Nghề đào tạo: " . htmlspecialchars($row['training_course']) . "<br>";
                echo "Thời gian học: " . htmlspecialchars($row['start_date']) . " đến " . htmlspecialchars($row['end_date']) . "<br>";
                echo "Ngày cấp: " . htmlspecialchars($row['issue_date']) . "<br>";
                // echo "<img src='data:image/jpeg;base64," . $row['CertificatePicture'] . "' width='800'/><br>";

                echo '<form action="lookup.php" method="POST">';
                echo '<input type="hidden" name="certificate_number" value="' . htmlspecialchars($row['certificate_number']) . '">';
                echo '<input type="hidden" name="create_copy" value="1">';
                echo '<input type="hidden" name="email" value="' . htmlspecialchars($row['email']) . '">';
                echo '<input type="hidden" name="verification_code" value="' . htmlspecialchars($verification_code) . '">';
                echo '<button type="submit">Tạo bản sao chứng chỉ</button>';
                echo '</form>';
                echo '</div>';

                if ($create_copy) {
                    $certificate_number = isset($_POST['certificate_number']) ? $_POST['certificate_number'] : null;
                    $stmt = $conn->prepare("SELECT CertificatePicture FROM certificates WHERE certificate_number = :certificate_number");
                    $stmt->bindParam(':certificate_number', $certificate_number);
                    $stmt->execute();
            
                    if ($stmt->rowCount() > 0) {
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        echo '<div style="text-align: center; margin-top: 20px;">';
                        echo "<h2>Ảnh toàn bộ chứng chỉ</h2>";
                        echo "<img src='data:image/jpeg;base64," . $row['CertificatePicture'] . "' width='800'/><br>";
                        echo '</div>';
                    } else {
                        echo "Không tìm thấy chứng chỉ.";
                    }
                }
        } else {
            echo "Mã xác minh không hợp lệ.";
        }
    } else {
        echo "Dữ liệu không hợp lệ.";
    }

}
}
