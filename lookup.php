<?php
// Khai báo các lỗi PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// use Google_Client;

session_start();
require_once 'db.php';
require_once 'vendor/autoload.php';


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
                                font-family: Arial, sans-serif;
                                margin: 0;
                                padding: 0;
                                background: linear-gradient(120deg, #c33764 0%, #1d2671 100%);
                                display: flex;
                                justify-content: center;
                                align-items: center;
                                height: 100vh;
                            }
                            .container {
                                background: rgba(255, 255, 255, 0.9);
                                padding: 20px;
                                border-radius: 8px;
                               box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                                width: 90%;
                                max-width: 600px;
                                text-align: center;
                            }
                            h1 {
                                color: #333;
                                margin-bottom: 20px;
                            }
                            .info {
                                border-top: 1px solid #ccc;
                                padding-top: 20px;
                            }
                            .info p {
                                margin: 10px 0;
                                font-size: 16px;
                                line-height: 1.6;
                            }
                            .form-container {
                                margin-top: 20px;
                            }
                            .form-container button {
                                padding: 10px 20px;
                                background-color: #4CAF50;
                                color: #fff;
                                border: none;
                                cursor: pointer;
                                border-radius: 4px;
                                font-size: 16px;
                                transition: background-color 0.3s ease;
                            }
                            .form-container button:hover {
                                background-color: #45a049;
                            }
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
                        <div class= "container">
                            <h1>Thông tin chứng chỉ</h1>
                            <p>Mã số chứng chỉ: ' . htmlspecialchars($row['certificate_number']) . '</p>
                            <p>Họ tên: ' . htmlspecialchars($row['full_name']) . '</p>
                            <p>Năm sinh: ' . htmlspecialchars($row['birth_year']) . '</p>
                            <p>Giới tính: ' . htmlspecialchars($row['gender']) . '</p>
                            <p>Nghề đào tạo: ' . htmlspecialchars($row['training_course']) . '</p>
                            <p>Thời gian học: ' . htmlspecialchars($row['start_date']) . ' đến ' . htmlspecialchars($row['end_date']) . '</p>
                            <p>Ngày cấp: ' . htmlspecialchars($row['issue_date']) . '</p>
                            <div class="form-container">
                                <form action="lookup.php" method="POST">
                                    <input type="hidden" name="certificate_number" value="' . htmlspecialchars($row['certificate_number']) . '">
                                    <input type="hidden" name="create_copy" value="1">
                                    <input type="hidden" name="email" value="' . htmlspecialchars($row['email']) . '">
                                    <button type="submit">Tạo bản sao chứng chỉ</button>
                                </form>
                            </div>
                        </div>';

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
            // sử dụng 1 trong 2 cái này để có ảnh
            // echo "<img src='data:image/jpeg;base64," . $row['CertificatePicture'] . "' width='800'/><br>";
            echo '<img src="data:image/jpeg;base64,' . base64_encode($row['CertificatePicture']) . '" width="800"/>';
            echo '</div>';
        } else {
            echo "Không tìm thấy chứng chỉ.";
        }
    }
} else {
    // echo "Không có Google token";

}
