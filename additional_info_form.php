<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tra cứu chứng chỉ</title>
    <script src="https://accounts.google.com/gsi/client" async defer></script> <!-- Sử dụng Google Identity Services -->
    <meta name="google-signin-client_id" content="199805261899-aavu0vckkke7f4mr6f0589mo392hbrp4.apps.googleusercontent.com">
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
