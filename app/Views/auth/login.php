<?php

// Define a constant
define("APP_NAME", "Student Management System");

// sanitizing input
function sanitize($value)
{
    return htmlspecialchars(trim($value));
}

// session 
if (!session_id()) {
    session_start();
}

$errorMessage = $error ?? "";  // from controller using extract()
$isError = !empty($errorMessage);

$tips = [
    "Use a strong password",
    "Do not share your credentials",
    "Logout after use"
];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> - Login</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            width: 350px;
            padding: 25px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        .login-container h2 {
            text-align: center;
            font-size: 22px;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 12px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        button {
            width: 100%;
            background: #007bff;
            padding: 10px;
            color: white;
            border: none;
            border-radius: 4px;
        }

        .tips {
            background: #eef5ff;
            padding: 10px;
            margin-top: 15px;
            border-radius: 6px;
            font-size: 14px;
        }

        .tips ul {
            margin: 0;
            padding: 0 0 0 18px;
        }
    </style>
</head>

<body>

    <div class="login-container">

        <h2><?= APP_NAME ?> Login</h2>

        <?php if ($isError): ?>
            <p class="error"><?= sanitize($errorMessage); ?></p>
        <?php endif; ?>

        <form method="POST" action="/login">
            <input
                type="text"
                name="username"
                placeholder="Enter Username"
                value="<?= isset($_POST['username']) ? sanitize($_POST['username']) : '' ?>"
                required>

            <input
                type="password"
                name="password"
                placeholder="Enter Password"
                required>

            <button type="submit">Login</button>
        </form>

        <div class="tips">
            <strong>Login Tips:</strong>
            <ul>
                <?php foreach ($tips as $tip): ?>
                    <li><?= sanitize($tip) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

</body>

</html>