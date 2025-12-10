<?php
require_once __DIR__ . '/../app/config/bootstrap.php';

use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\StudentController;
use App\Controllers\AdminController;
use App\Middleware\AuthMiddleware;
use App\Controllers\CourseController;
use App\Controllers\StudentProfileController;
use App\Controllers\TeacherController;

/**
 * Application Router
 * 
 * This file serves as the entry point for routing in the application. It defines routes
 * for authentication, admin and student functionalities, and uses middleware
 * for role-based access control.
 */
$router = new Router();

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (Protected)
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
    AuthMiddleware::check(); // Ensure user is authenticated
    AuthMiddleware::checkRole(['admin']); // Ensure user has admin role
    (new AdminController())->dashboard();
});

$router->get('/teachers', function () {
    AuthMiddleware::checkRole(['admin']);
    (new TeacherController())->index();
});

$router->get('/teachers/add', function () {
    AuthMiddleware::checkRole(['admin']);
    (new TeacherController())->createPage();
});

$router->post('/teachers/add', function () {
    AuthMiddleware::checkRole(['admin']);
    (new TeacherController())->store();
});

$router->get('/teachers/edit', function () {
    AuthMiddleware::checkRole(['admin']);
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    (new TeacherController())->editPage();
});

$router->post('/teachers/update', function () {
    AuthMiddleware::checkRole(['admin']);
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    (new TeacherController())->update($id);
});

$router->get('/teachers/delete', function () {
    AuthMiddleware::checkRole(['admin']);
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    (new TeacherController())->delete($id);
});
$router->get('/student', function () {
    AuthMiddleware::checkRole(['admin']);
    (new StudentController())->index();
});

$router->get('/student/add', function () {
    AuthMiddleware::checkRole(['admin']);
    (new StudentController())->createPage();
});

$router->post('/student/add', function () {
    AuthMiddleware::checkRole(['admin']);
    (new StudentController())->store();
});

$router->get('/student/edit', function () {
    AuthMiddleware::checkRole(['admin']);
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    (new StudentController())->editPage($id);
});

$router->post('/student/update', function () {
    AuthMiddleware::checkRole(['admin']);
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    (new StudentController())->update($id);
});

$router->get('/student/delete', function () {
    AuthMiddleware::checkRole(['admin']);
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    (new StudentController())->delete($id);
});

$router->get('/course/list', function () {
    AuthMiddleware::checkRole(['admin']);
    (new CourseController())->index();
});

$router->get('/course/edit', function () {
    AuthMiddleware::checkRole(['admin']);
    $id = $_POST['id'] ?? null;
    (new CourseController())->editPage($id);
});

$router->post('/course/update', function () {
    AuthMiddleware::checkRole(['admin']);
    $id = $_POST['id'] ?? null;
    (new CourseController())->update($id);
});

$router->get('/course/add', function () {
    AuthMiddleware::checkRole(['admin']);
    (new CourseController())->createPage();
});

$router->post('/course/add', function () {
    AuthMiddleware::checkRole(['admin']);
    (new CourseController())->store();
});

$router->get('/course/delete', function () {
    AuthMiddleware::checkRole(['admin']);
    $id = $_GET['id'] ?? null;
    (new CourseController())->delete($id);
});

$router->get('/assign-courses', function () {
    AuthMiddleware::checkRole(['admin']);
    $id = $_GET['id'] ?? null;
    (new AdminController())->assignCoursesPage($id);
});

$router->post('/assign-courses/assign', function () {
    AuthMiddleware::checkRole(['admin']);
    $id = $_GET['id'] ?? null;
    (new AdminController())->assignCourses($id);
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

$router->get('/student/profile', function () {
    AuthMiddleware::checkRole(['student']);
    (new StudentProfileController())->viewProfile();
});

$router->post('/profile/update', function () {
    AuthMiddleware::checkRole(['student']);
    (new StudentProfileController())->updateProfile();
});

$router->get('/student/course', function () {
    AuthMiddleware::checkRole(['student']);
    (new StudentProfileController())->courses();
});

$router->get('/student/change_password', function () {
    AuthMiddleware::checkRole(['student']);
    (new StudentProfileController())->changePassword();
});

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
    (new TeacherController())->dashboard();
});


// Resolve the current request
$router->resolve();
