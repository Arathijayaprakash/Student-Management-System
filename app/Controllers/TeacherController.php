<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Services\PaginationService;
use App\Models\Course;
use App\Models\User;
use App\Models\Teacher;

/**
 * Teacher Controller
 * 
 * Handles student-related functionalities, including listing, creating, editing, updating, and deleting students.
 */
class TeacherController extends Controller
{
    /**
     * @var PaginationService
     */
    private $paginationService;

    /**
     * Constructor
     * Initializes the PaginationService.
     */
    public function __construct()
    {
        $this->paginationService = new PaginationService();
    }

    /**
     * Display a paginated list of students.
     * Supports search functionality.
     * 
     * @return void
     */
    public function index(): void
    {
        $teacherModel = new Teacher();

        //pagination variables
        $limit = 4; // Students per page
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

        //search filter
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        // Use PaginationService to get paginated students
        $paginationData = $this->paginationService->paginate(
            fn($search, $limit, $offset) => $teacherModel->getFilteredPaginated($search, $limit, $offset),
            fn($search) => $teacherModel->countFiltered($search),
            $limit,
            $page,
            $search
        );

        $this->view(
            "admin/teachers/list",
            [
                "title" => "Teacher List",
                "teachers" => $paginationData['items'],
                "page" => $paginationData['page'],
                "totalPages" => $paginationData['totalPages'],
                "search" => $paginationData['search'],
            ],
            layout: "admin"
        );
    }

    /**
     * Display the teacher creation page.
     * 
     * @return void
     */
    public function createPage(): void
    {

        $courses = (new Course())->getAll();
        // Render the teacher creation view
        $this->view(
            "admin/teachers/add",
            [
                "title" => "Add Teacher",
                "courses" => $courses
            ],
            layout: "admin"
        );
    }

    /**
     * Handle the creation of a new teacher.
     * 
     * @return void
     */
    public function store(): void
    {
        $userModel = new User();
        $teacherModel = new Teacher();

        // Auto-generate password
        $plainPassword = $_POST['password']; // 8 chars
        $hashedPassword = password_hash($plainPassword, PASSWORD_BCRYPT);

        // 1️⃣ Insert into users table
        $userId = $userModel->create([
            "name"     => $_POST['name'],
            "email"    => $_POST['email'],
            "password" => $hashedPassword,
            "role"     => "teacher"
        ]);

        if (!$userId) {
            $this->view("admin/teachers/add", [
                "error" => "Failed to create user"
            ], "admin");
        }

        // Insert into teachers table
        $teacherModel->create([
            "user_id" => $userId,
            "phone" => $_POST['phone'],
            "qualification" => $_POST['qualification']
        ]);

        //pagination variables
        $limit = 4; // Students per page
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

        //search filter
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        // Use PaginationService to get paginated students
        $paginationData = $this->paginationService->paginate(
            fn($search, $limit, $offset) => $teacherModel->getFilteredPaginated($search, $limit, $offset),
            fn($search) => $teacherModel->countFiltered($search),
            $limit,
            $page,
            $search
        );
        $this->view(
            "admin/teachers/list",
            [
                "title" => "Teacher List",
                "teachers" => $paginationData['items'],
                "page" => $paginationData['page'],
                "totalPages" => $paginationData['totalPages'],
                "search" => $paginationData['search'],
                "success"  => "Teacher created successfully!",
                "password" => $plainPassword,
            ],
            layout: "admin"
        );
    }

    public function dashboard()
    {
        return $this->view("teacher/dashboard", [], "teacher");
    }

    /**
     * Display the teacher edit page.
     * 
     * @return void
     */
    public function editPage(): void
    {
        $id = $_GET['id'];
        $teacher = (new Teacher())->findById($id);
        $courses = (new Course())->getAll();

        $this->view(
            "admin/teachers/edit",
            [
                "title" => "Edit Student",
                "teacher" => $teacher,
                "courses" => $courses,
            ],
            layout: "admin"
        );
    }

    /**
     * Handle the update of an existing teacher.
     * 
     * @return void
     */
    public function update()
    {
        $teacherModel = new Teacher();
        $userModel = new User();

        $id = $_POST['id'];
        $teacher = $teacherModel->findById($id);

        // Update user table
        $userModel->update($teacher['user_id'], [
            "name" => $_POST['name'],
            "email" => $_POST['email']
        ]);

        // Update teacher table
        $teacherModel->update($id, [
            "phone" => $_POST['phone'],
            "qualification"  => $_POST['qualification'],
        ]);

        header("Location: /teachers?updated=1");
        exit;
        exit;
    }

    /**
     * Handle the deletion of a teacher.
     * 
     * @return void
     */
    public function delete()
    {
        $id = $_GET['id'];

        $teacherModel = new Teacher();
        $teacher = $teacherModel->findById($id);

        if ($teacher) {
            $userId = $teacher['user_id'];

            // delete user → teacher auto deleted because of ON DELETE CASCADE
            (new User())->delete($userId);
        }

        header("Location: /teachers?deleted=1");
        exit;
    }
}
