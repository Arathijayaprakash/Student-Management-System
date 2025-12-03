<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Student Dashboard</title>
</head>

<body>
    <h1>Student Dashboard</h1>
    <p>Welcome <?= htmlspecialchars($_SESSION['user']['username']) ?></p>
    <p><a href="/logout">Logout</a></p>
</body>

</html>