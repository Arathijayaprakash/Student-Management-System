<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title><?= isset($title) ? htmlspecialchars($title) : 'Teacher Dashboard' ?></title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        header {
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            text-align: center;
            font-size: 22px;
            font-weight: bold;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #222;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
        }

        .sidebar h3 {
            color: #fff;
            text-align: center;
            margin-bottom: 15px;
            font-size: 20px;
            letter-spacing: 1px;
        }

        .sidebar a {
            display: block;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            font-size: 16px;
            border-bottom: 1px solid #333;
        }

        .sidebar a:hover {
            background-color: #444;
        }

        .main-content {
            margin-left: 250px;
            padding: 30px;
        }

        h1 {
            color: #333;
        }

        .welcome {
            margin-bottom: 20px;
            font-size: 18px;
        }

        .card-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <header>
        Teacher Dashboard
    </header>

    <div class="sidebar">
        <h3>Menu</h3>
        <a href="/teacher/dashboard">🏠 Dashboard</a>
        <a href="/teacher/profile">👤 Profile</a>
        <a href="/teacher/courses">📘 Assigned Courses</a>
        <a href="/teacher/change_password">🔒 Change Password</a>
        <a href="/logout" style="margin-top:20px; background:#c82333;">🚪 Logout</a>
    </div>
    <div class="main-content">
        <?php if (isset($content)) echo $content; ?>
    </div>

</body>

</html>