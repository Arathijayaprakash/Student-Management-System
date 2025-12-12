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
        /* General Styles */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #007bff, #6c757d);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.5s ease-in-out;
        }

        .login-container h2 {
            text-align: center;
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }

        .error {
            color: #dc3545;
            text-align: center;
            margin-bottom: 15px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        input:focus {
            border-color: #007bff;
            outline: none;
        }

        button {
            width: 100%;
            background: #007bff;
            padding: 12px;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background: #0056b3;
        }

        .tips {
            background: #eef5ff;
            padding: 15px;
            margin-top: 20px;
            border-radius: 8px;
            font-size: 14px;
            color: #333;
        }

        .tips ul {
            margin: 0;
            padding: 0 0 0 18px;
        }

        .tips ul li {
            margin-bottom: 8px;
        }

        /* Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .login-container {
                padding: 20px;
            }

            input,
            button {
                font-size: 14px;
            }
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
   
    </div>

</body>

</html>