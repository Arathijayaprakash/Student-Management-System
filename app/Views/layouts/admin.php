<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title><?= isset($title) ? htmlspecialchars($title) : 'Admin Dashboard' ?></title>
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
            padding: 10px 20px;
            text-align: center;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #333;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
        }

        .sidebar a {
            display: block;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            font-size: 16px;
        }

        .sidebar a:hover {
            background-color: #575757;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        h1 {
            color: #333;
        }

        .welcome {
            margin-bottom: 20px;
            font-size: 18px;
        }
    </style>
</head>

<body>
    <header>
        <h1>Admin Dashboard</h1>
    </header>
    <div class="sidebar">
        <a href="/admin/dashboard">Home</a>
        <a href="/student/add">Add Student</a>
        <a href="/student">View Students</a>
        <a href="/courses/add">Add Courses</a>
        <a href="/courses/view">View Courses</a>
        <a href="/logout">Logout</a>
    </div>
    <div class="main-content">
        <?php if (isset($content)) echo $content; ?>
    </div>
</body>

</html>