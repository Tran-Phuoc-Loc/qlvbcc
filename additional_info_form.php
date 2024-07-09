<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tra cứu chứng chỉ</title>
    <script src="https://accounts.google.com/gsi/client" async defer></script> <!-- Sử dụng Google Identity Services -->
    <meta name="google-signin-client_id" content="199805261899-aavu0vckkke7f4mr6f0589mo392hbrp4.apps.googleusercontent.com">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            width: 100%;
            max-width: 360px;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            font-size: 24px;
        }
        form {
            text-align: center;
        }
        label {
            display: block;
            margin-bottom: 10px;
            color: #666;
            font-size: 14px;
            text-transform: uppercase;
        }
        input[type="text"] {
            width: calc(100% - 20px);
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }
        #buttonDiv {
            margin-bottom: 20px;
            text-align: center;
        }
        button[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 16px;
            width: 100%;
            max-width: 200px;
            margin: 0 auto;
            display: block;
        }
        button[type="submit"]:hover {
            background-color: #45a049;
        }
        /* Google Sign-In Button Styles */
        #googleSignInBtn {
            margin-top: 20px;
        }
        /* Responsiveness */
        @media (max-width: 480px) {
            .container {
                padding: 20px;
            }
            h1 {
                font-size: 20px;
            }
            input[type="text"] {
                width: calc(100% - 16px);
                padding: 10px;
            }
            button[type="submit"] {
                padding: 12px 16px;
            }
        }
    </style>
    <script>
        function handleCredentialResponse(response) {
            var id_token = response.credential;
            console.log("Credential ID Token: " + id_token); // kiểm tra mã token
            document.getElementById("google_token").value = id_token;
            document.getElementById("lookupForm").submit();
        }

        window.onload = function () {
            google.accounts.id.initialize({
                client_id: "199805261899-aavu0vckkke7f4mr6f0589mo392hbrp4.apps.googleusercontent.com",
                callback: handleCredentialResponse
            });
            google.accounts.id.renderButton(
                document.getElementById("buttonDiv"),
                { theme: "outline", size: "large" }  // tùy chỉnh thuộc tính
            );
            // google.accounts.id.prompt(); // hiển thị hộp thoại
        }
    </script>
</head>
<body>
    <h1>Tra cứu chứng chỉ</h1>
    <form id="lookupForm" action="lookup.php" method="POST" accept-charset="UTF-8">
        <label for="certificate_number">Số chứng chỉ:</label>
        <input type="text" id="certificate_number" name="certificate_number" required>
        <input type="hidden" name="email" value="<?php echo htmlspecialchars($_GET['email']); ?>">
        <div id="buttonDiv"></div>
        <input type="hidden" id="google_token" name="google_token">
        <!-- <button type="submit">Tra cứu</button> -->
    </form>
</body>
</html>
