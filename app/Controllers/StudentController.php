<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Student;
use App\Models\User;
use App\Models\Course;
use App\Services\PaginationService;

/**
 * Student Controller
 * 
 * Handles student-related functionalities, including listing, creating, editing, updating, and deleting students.
 */
class StudentController extends Controller
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
        $studentModel = new Student();

        //pagination variables
        $limit = 4; // Students per page
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

        //search filter
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        // Use PaginationService to get paginated students
        $paginationData = $this->paginationService->paginate(
            fn($search, $limit, $offset) => $studentModel->getFilteredPaginated($search, $limit, $offset),
            fn($search) => $studentModel->countFiltered($search),
            $limit,
            $page,
            $search
        );
        $this->view(
            "admin/student/studentList",
            [
                "title" => "Student List",
                "students" => $paginationData['items'],
                "page" => $paginationData['page'],
                "totalPages" => $paginationData['totalPages'],
                "search" => $paginationData['search'],
            ],
            layout: "admin"
        );
    }

    /**
     * Display the student creation page.
     * 
     * @return void
     */
    public function createPage(): void
    {
        $courses = (new Course())->getAll();

        // Render the student creation view
        $this->view(
            "admin/student/add",
            [
                "title" => "Add Student",
                "courses" => $courses
            ],
            layout: "admin"
        );
    }

    /**
     * Handle the creation of a new student.
     * 
     * @return void
     */
    public function store(): void
    {
        $userModel = new User();
        $studentModel = new Student();

        // Auto-generate password
        $plainPassword = $_POST['password']; // 8 chars
        $hashedPassword = password_hash($plainPassword, PASSWORD_BCRYPT);

        // 1️⃣ Insert into users table
        $userId = $userModel->create([
            "name"     => $_POST['name'],
            "email"    => $_POST['email'],
            "password" => $hashedPassword,
            "role"     => "student"
        ]);

        if (!$userId) {
            $this->view("admin/student/add", [
                "error" => "Failed to create user"
            ], "admin");
        }

        // Handle image upload
        $photoName = null;
        if (!empty($_FILES['photo']['name'])) {
            $uploadDir = __DIR__ . '/../../public/uploads/students/';
            // Create folder if not exists
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            // Allowed types
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            if (!in_array(strtolower($ext), $allowed)) {
                $this->view("admin/student/add", [
                    "error" => "Only JPG, PNG, WEBP images allowed"
                ], "admin");
            }
            // Generate unique file name
            $photoName = uniqid('stu_') . '.' . $ext;
            $targetPath = $uploadDir . $photoName;
            move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath);
        }

        // Insert into students table
        $studentModel->create([
            "user_id" => $userId,
            "course_id"  => $_POST["course_id"],
            "photo"   => $photoName

        ]);

        //pagination variables
        $limit = 4; // Students per page
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

        //search filter
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        // Use PaginationService to get paginated students
        $paginationData = $this->paginationService->paginate(
            fn($search, $limit, $offset) => $studentModel->getFilteredPaginated($search, $limit, $offset),
            fn($search) => $studentModel->countFiltered($search),
            $limit,
            $page,
            $search
        );
        $this->view(
            "admin/student/studentList",
            [
                "title" => "Student List",
                "students" => $paginationData['items'],
                "page" => $paginationData['page'],
                "totalPages" => $paginationData['totalPages'],
                "search" => $paginationData['search'],
                "success"  => "Student created successfully!",
                "password" => $plainPassword,
            ],
            layout: "admin"
        );
    }

    /**
     * Display the student edit page.
     * 
     * @return void
     */
    public function editPage(): void
    {
        $id = $_GET['id'];
        $student = (new Student())->findById($id);
        $courses = (new Course())->getAll();

        $this->view(
            "admin/student/edit",
            [
                "title" => "Edit Student",
                "student" => $student,
                "courses" => $courses,
            ],
            layout: "admin"
        );
    }

    /**
     * Handle the update of an existing student.
     * 
     * @return void
     */
    public function update()
    {
        $studentModel = new Student();
        $userModel = new User();

        $id = $_POST['id'];
        $student = $studentModel->findById($id);

        // Update user table
        $userModel->update($student['user_id'], [
            "name" => $_POST['name'],
            "email" => $_POST['email']
        ]);

        // Upload new photo if updated
        $photoName = $student['photo'];
        if (!empty($_FILES['photo']['name'])) {
            $photoName = "stu_" . uniqid() . ".png";
            move_uploaded_file($_FILES['photo']['tmp_name'], __DIR__ . "/../../public/uploads/students/" . $photoName);
        }

        // Update student table
        $studentModel->update($id, [
            "course_id" => $_POST['course_id'],
            "photo"  => $photoName
        ]);

        header("Location: /student?updated=1");
        exit;
        exit;
    }

    /**
     * Handle the deletion of a student.
     * 
     * @return void
     */
    public function delete()
    {
        $id = $_GET['id'];

        $studentModel = new Student();
        $student = $studentModel->findById($id);

        if ($student) {
            $userId = $student['user_id'];

            // delete user → student auto deleted because of ON DELETE CASCADE
            (new User())->delete($userId);
        }

        header("Location: /student?deleted=1");
        exit;
    }
}
