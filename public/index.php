<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Student Management System</h1>
</body>

</html>

<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;
use App\Controllers\AuthController;

$router = new Router();
$router->get('/', [AuthController::class, 'loginPage']);
$router->resolve();
?>