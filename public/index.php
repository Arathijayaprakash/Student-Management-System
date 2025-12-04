<?php
require_once __DIR__ . '/../app/config/bootstrap.php';
use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\StudentController;
use App\Controllers\AdminController;
use App\Middleware\AuthMiddleware;

$router = new Router();

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/
$router->get('/', [AuthController::class, 'loginPage']);
$router->get('/login', [AuthController::class, 'loginPage']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (Protected)
|--------------------------------------------------------------------------
*/
$router->get('/admin/dashboard', function () {
    AuthMiddleware::checkRole(['admin']);
    (new AdminController())->dashboard();
});
$router->get('/student', [StudentController::class, 'index']);

$router->get('/student/add', function () {
    AuthMiddleware::checkRole(['admin']);
    (new StudentController())->createPage();
});

$router->post('/student/add', function () {
    AuthMiddleware::checkRole(['admin']);
    (new StudentController())->store();
});

/*
|--------------------------------------------------------------------------
| STUDENT ROUTES (Protected)
|--------------------------------------------------------------------------
*/
$router->get('/student/dashboard', function () {
    AuthMiddleware::checkRole(['student']);
    include __DIR__ . '/../app/Views/student/dashboard.php';
});

/*
|--------------------------------------------------------------------------
| TEACHER ROUTES (Protected)
|--------------------------------------------------------------------------
*/
$router->get('/teacher/dashboard', function () {
    AuthMiddleware::checkRole(['teacher']);
    echo "Teacher dashboard";
});


$router->resolve();