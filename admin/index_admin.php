<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Thông tin Chứng chỉ</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            min-height: 100vh;
            margin: 0;
        }

        .navbar {
            background-color: #007bff;
            color: white;
            width: 250px;
            padding: 20px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .navbar-brand {
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 1S0px;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .navbar-nav {
            width: 100%;
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .nav-item {
            margin-bottom: 10px;
        }

        .nav-link {
            color: white;
            text-decoration: none;
            font-size: 1.2rem;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            display: block;
            word-wrap: break-word;
            max-width: 100%;
        }

        .nav-link:hover {
            background-color: #8470FF;
        }

        .content {
            flex-grow: 1;
            padding: 30px;
        }

        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            color: #6c757d;
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <a class="navbar-brand" href="#">Quản lý Thông tin </a>
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="admin.php">Quản lý Thông tin</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="report.php">Báo cáo Số lượng</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="insert_data.php">Chèn Dữ liệu</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="delete_data.php">Xóa Dữ liệu</a>
            </li>
        </ul>
    </nav>

    <div class="content">
        <div class="container">
            <!-- Nội dung của trang web -->
            <h1>Chào mừng đến với Quản lý Thông tin Chứng chỉ</h1>
            <p>Đây là trang chủ của hệ thống.</p>
        </div>

        <div class="footer">
            &copy; <?php echo date("Y"); ?> Quản lý Thông tin Chứng chỉ. All rights reserved.
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>