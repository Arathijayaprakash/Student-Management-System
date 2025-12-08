<?php
require_once __DIR__ . '/../app/config/bootstrap.php';

use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\StudentController;
use App\Controllers\AdminController;
use App\Middleware\AuthMiddleware;
use App\Controllers\CourseController;
use App\Controllers\StudentProfileController;

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
// Edit Student
$router->get('/student/edit', function () {
    AuthMiddleware::checkRole(['admin']);
    $id = $_GET['id'] ?? null;
    (new StudentController())->editPage($id);
});
$router->post('/student/update', function () {
    AuthMiddleware::checkRole(['admin']);
    $id = $_POST['id'] ?? null;
    (new StudentController())->update($id);
});

// Delete Student
$router->get('/student/delete', function () {
    AuthMiddleware::checkRole(['admin']);
    $id = $_GET['id'] ?? null;
    (new StudentController())->delete($id);
});

$router->get('/course/list', function () {
    AuthMiddleware::checkRole(['admin']);
    (new CourseController())->index();
});
$router->get('/courses', [CourseController::class, 'index']);

$router->get('/course/add', function () {
    AuthMiddleware::checkRole(['admin']);
    (new CourseController())->createPage();
});

$router->post('/course/add', function () {
    AuthMiddleware::checkRole(['admin']);
    (new CourseController())->store();
});

/*
|--------------------------------------------------------------------------
| STUDENT ROUTES (Protected)
|--------------------------------------------------------------------------
*/
$router->get('/student/dashboard', function () {
    AuthMiddleware::checkRole(['student']);
    (new StudentProfileController())->dashboard();
});
// View Student Profile
$router->get('/student/profile', function () {
    AuthMiddleware::checkRole(['student']);
    (new StudentProfileController())->viewProfile();
});
//update profile
$router->post('/profile/update', function () {
    AuthMiddleware::checkRole(['student']);
    (new StudentProfileController())->updateProfile();
});
// View Course
$router->get('/student/course', function () {
    AuthMiddleware::checkRole(['student']);
    (new StudentProfileController())->courses();
});
// Change Password - GET (Display the form)
$router->get('/student/change_password', function () {
    AuthMiddleware::checkRole(['student']);
    (new StudentProfileController())->changePassword();
});

// Change Password - POST (Handle form submission)
$router->post('/student/change_password', function () {
    AuthMiddleware::checkRole(['student']);
    (new StudentProfileController())->changePassword();
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
