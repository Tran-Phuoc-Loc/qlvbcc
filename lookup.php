<?php
// Khai báo các lỗi PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// use Google_Client;

session_start();
require_once 'db.php';
require_once 'vendor/autoload.php';

// Xóa phiên người dùng khi trang được tải lại
// if (!isset($_SESSION['loggedin'])) {
//     session_destroy();
//     header('Location: index.php'); // Chuyển hướng đến trang đăng nhập
//     exit;
// }
// if ($conn) {
//     echo "Kết nối cơ sở dữ liệu thành công.";
// } else {
//     echo "Không thể kết nối cơ sở dữ liệu.";
// }

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $google_token = $_POST['google_token'];

//     // Xác minh Google token
//     $client = new Google_Client(['client_id' => '199805261899-aavu0vckkke7f4mr6f0589mo392hbrp4.apps.googleusercontent.com']); // Thay YOUR_CLIENT_ID bằng Client ID của bạn
//     $payload = $client->verifyIdToken($google_token);
//     if ($payload) {
//         // Token hợp lệ
//         echo "Token hợp lệ";
//     } else {
//         // Token không hợp lệ
//         echo "Token không hợp lệ";
//         exit;
//     }
//     $email = isset($_POST['email']) ? $_POST['email'] : null;
//     $certificate_number = isset($_POST['certificate_number']) ? $_POST['certificate_number'] : null;
//     $create_copy = isset($_POST['create_copy']) ? $_POST['create_copy'] : null;
//     if ($email && $certificate_number) {
//         echo "Email và số chứng chỉ hợp lệ";
//     } else {
//         echo "Dữ liệu không hợp lệ";
//         exit;
//     }
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Hiển thị nội dung của $_POST để kiểm tra
    // echo '<pre>';
    // print_r($_POST);
    // echo '</pre>';

    $google_token = isset($_POST['google_token']) ? $_POST['google_token'] : null;
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $certificate_number = isset($_POST['certificate_number']) ? $_POST['certificate_number'] : null;
    $create_copy = isset($_POST['create_copy']) ? $_POST['create_copy'] : null;

    if ($google_token) {
        $client = new Google_Client(['client_id' => '199805261899-aavu0vckkke7f4mr6f0589mo392hbrp4.apps.googleusercontent.com']);
        $payload = $client->verifyIdToken($google_token);
        if ($payload) {
            // echo "Google token hợp lệ";

            // $_SESSION['loggedin'] = true; // Đánh dấu người dùng đã đăng nhập

            if ($email && $certificate_number) {
                // Truy vấn cơ sở dữ liệu để lấy thông tin chứng chỉ
                $stmt = $conn->prepare("SELECT * FROM certificates WHERE email = :email AND certificate_number = :certificate_number");
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':certificate_number', $certificate_number);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    // echo '<pre>';
                    // print_r($row);
                    // echo '</pre>';
                } else {
                    echo "Không tìm thấy số chứng chỉ với email này.<br> ";
                    echo "Xin vui lòng liên hệ người quản trị qua email này.";
                    exit;
                }

                echo '<!DOCTYPE html>
                    <html lang="vi">
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <title>Thông tin chứng chỉ</title>
                        <style>
                            body {
                                -webkit-user-select: none; /* Chặn lựa chọn trên WebKit (Chrome, Safari) */
                                -moz-user-select: none; /* Chặn lựa chọn trên Firefox */
                                -ms-user-select: none; /* Chặn lựa chọn trên IE/Edge */
                                user-select: none; /* Chặn lựa chọn trên trình duyệt hiện đại */
                            }
                                .no-select {
                                -webkit-user-select: none;
                                -moz-user-select: none;
                                -ms-user-select: none;
                                user-select: none;
                            }
                        </style>
                        <script>
                            // Chặn chuột phải
                            // document.addEventListener(\'contextmenu\', function(e) {
                            //     e.preventDefault();
                            // });

                            // Chặn tổ hợp phím copy, paste và chụp màn hình
                            document.addEventListener(\'keydown\', function(e) {
                                if ((e.ctrlKey && (e.key === \'c\' || e.key === \'v\' || e.key === \'p\' || e.key === \'x\')) || e.key === \'PrintScreen\') {
                                    e.preventDefault();
                                }
                            });

                            // Chặn phím PrintScreen
                            document.addEventListener(\'keyup\', function(e) {
                                if (e.key === \'PrintScreen\') {
                                    navigator.clipboard.writeText(\'Chụp màn hình đã bị vô hiệu hóa\');
                                    alert(\'Chụp màn hình đã bị vô hiệu hóa\');
                                }
                            });

                            // Chặn chụp màn hình qua JavaScript (Không hiệu quả hoàn toàn)
                            window.addEventListener(\'blur\', function() {
                                navigator.clipboard.writeText(\'\');
                            });

                            // Chặn chức năng kéo thả
                            document.addEventListener(\'dragstart\', function(e) {
                                e.preventDefault();
                            });
                        </script>
                    </head>
                    <body>
                        <div style="text-align: center;">
                            <h1>Thông tin chứng chỉ</h1>
                            <p>Mã số chứng chỉ: ' . htmlspecialchars($row['certificate_number']) . '</p>
                            <p>Họ tên: ' . htmlspecialchars($row['full_name']) . '</p>
                            <p>Năm sinh: ' . htmlspecialchars($row['birth_year']) . '</p>
                            <p>Giới tính: ' . htmlspecialchars($row['gender']) . '</p>
                            <p>Nghề đào tạo: ' . htmlspecialchars($row['training_course']) . '</p>
                            <p>Thời gian học: ' . htmlspecialchars($row['start_date']) . ' đến ' . htmlspecialchars($row['end_date']) . '</p>
                            <p>Ngày cấp: ' . htmlspecialchars($row['issue_date']) . '</p>
                            <form action="lookup.php" method="POST">
                                <input type="hidden" name="certificate_number" value="' . htmlspecialchars($row['certificate_number']) . '">
                                <input type="hidden" name="create_copy" value="1">
                                <input type="hidden" name="email" value="' . htmlspecialchars($row['email']) . '">
                                <button type="submit">Tạo bản sao chứng chỉ</button>
                            </form>
                        </div>';
                // echo '<div style="text-align: center;">';
                // echo "<h1>Thông tin chứng chỉ</h1>";
                // echo "Mã số chứng chỉ: " . htmlspecialchars($row['certificate_number']) . "<br>";
                // echo "Họ tên: " . htmlspecialchars($row['full_name']) . "<br>";
                // echo "Năm sinh: " . htmlspecialchars($row['birth_year']) . "<br>";
                // echo "Giới tính: " . htmlspecialchars($row['gender']) . "<br>";
                // echo "Nghề đào tạo: " . htmlspecialchars($row['training_course']) . "<br>";
                // echo "Thời gian học: " . htmlspecialchars($row['start_date']) . " đến " . htmlspecialchars($row['end_date']) . "<br>";
                // echo "Ngày cấp: " . htmlspecialchars($row['issue_date']) . "<br>";

                // echo '<form action="lookup.php" method="POST">';
                // echo '<input type="hidden" name="certificate_number" value="' . htmlspecialchars($row['certificate_number']) . '">';
                // echo '<input type="hidden" name="create_copy" value="1">';
                // echo '<input type="hidden" name="email" value="' . htmlspecialchars($row['email']) . '">';
                // echo '<button type="submit">Tạo bản sao chứng chỉ</button>';
                // echo '</form>';
                // echo '</div>';

                // if ($create_copy) {
                //     $stmt = $conn->prepare("SELECT CertificatePicture FROM certificates WHERE certificate_number = :certificate_number");
                //     $stmt->bindParam(':certificate_number', $certificate_number);
                //     $stmt->execute();

                //     if ($stmt->rowCount() > 0) {
                //         $row = $stmt->fetch(PDO::FETCH_ASSOC);
                //         echo '<div style="text-align: center; margin-top: 20px;">';
                //         echo "<h2>Ảnh toàn bộ chứng chỉ</h2>";
                //         echo "<img src='data:image/jpeg;base64," . $row['CertificatePicture'] . "' width='800'/><br>";
                //         echo '</div>';
                //     } else {
                //         echo "Không tìm thấy chứng chỉ.";
                //     }
                // }
            } else {
                echo "Không tìm thấy chứng chỉ với email và số chứng chỉ này.";
            }
        } else {
            echo "Dữ liệu không hợp lệ.";
        }
    } 
    // else {
    //     echo "Google token không hợp lệ";
    // }
    if ($create_copy) {
        $stmt = $conn->prepare("SELECT CertificatePicture FROM certificates WHERE certificate_number = :certificate_number");
        $stmt->bindParam(':certificate_number', $certificate_number);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            echo '<script>
                            // Chặn chuột phải
                            // document.addEventListener(\'contextmenu\', function(e) {
                            //     e.preventDefault();
                            // });

                            // Chặn tổ hợp phím copy, paste và chụp màn hình
                            document.addEventListener(\'keydown\', function(e) {
                                if ((e.ctrlKey && (e.key === \'c\' || e.key === \'v\' || e.key === \'p\' || e.key === \'x\')) || e.key === \'PrintScreen\') {
                                    e.preventDefault();
                                }
                            });

                            // Chặn phím PrintScreen
                            document.addEventListener(\'keyup\', function(e) {
                                if (e.key === \'PrintScreen\') {
                                    navigator.clipboard.writeText(\'Chụp màn hình đã bị vô hiệu hóa\');
                                    alert(\'Chụp màn hình đã bị vô hiệu hóa\');
                                }
                            });

                            // Chặn chụp màn hình qua JavaScript (Không hiệu quả hoàn toàn)
                            window.addEventListener(\'blur\', function() {
                                navigator.clipboard.writeText(\'\');
                            });

                            // Chặn chức năng kéo thả
                            document.addEventListener(\'dragstart\', function(e) {
                                e.preventDefault();
                            });
                        </script> ';
            echo '<div style="text-align: center; margin-top: 20px;">';
            echo "<h2>Ảnh toàn bộ chứng chỉ</h2>";
            echo "<img src='data:image/jpeg;base64," . $row['CertificatePicture'] . "' width='800'/><br>";
            echo '</div>';
        } else {
            echo "Không tìm thấy chứng chỉ.";
        }
    }
} else {
    // echo "Không có Google token";
    
} 


