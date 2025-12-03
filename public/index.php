<?php
// public/index.php

require_once __DIR__ . '/../app/config/bootstrap.php';

use App\Core\Router;
use App\Controllers\AuthController;
use App\Middleware\AuthMiddleware;

$router = new Router();

// Auth routes
$router->get('/', [AuthController::class, 'loginPage']); // default to login
$router->get('/login', [AuthController::class, 'loginPage']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);

// Dashboards (protected)
$router->get('/admin/dashboard', function () {
    \App\Middleware\AuthMiddleware::checkRole(['admin']);
    include __DIR__ . '/../app/Views/admin/dashboard.php';
});
$router->get('/student/dashboard', function () {
    \App\Middleware\AuthMiddleware::checkRole(['student']);
    include __DIR__ . '/../app/Views/student/dashboard.php';
});
$router->get('/teacher/dashboard', function () {
    \App\Middleware\AuthMiddleware::checkRole(['teacher']);
    echo 'Teacher dashboard (build later)';
});

// Resolve request
$router->resolve();
