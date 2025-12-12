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
    public function profile()
    {
        // Get logged-in teacher ID from session
        $userId = $_SESSION['user']['id'];
        // Fetch teacher details
        $teacher = (new Teacher())->findByUserId($userId);

        return $this->view("teacher/profile", [
            "teacher" => $teacher,
        ], "teacher");
    }
    public function assignedCourses()
    {
        // Get the logged-in teacher's user ID from the session
        $userId = $_SESSION['user']['id'];

        // Fetch the teacher's details using their user ID
        $teacher = (new Teacher())->findByUserId($userId);

        if (!$teacher) {
            // Handle case where teacher is not found
            header("Location: /teacher/dashboard?error=Teacher not found");
            exit;
        }

        // Fetch the assigned courses for the teacher
        $courses = (new Teacher())->getAssignedCourses($teacher['id']);

        // Render the view with the assigned courses
        $this->view("/teacher/assigned-courses", [
            "title" => "Assigned Courses",
            "courses" => $courses,
        ], "teacher");
    }

    public function updateProfile()
    {
        // Check if the form is submitted via POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $qualification = $_POST['qualification'] ?? '';

            // Validate input
            if (empty($name) || empty($email) || empty($phone) || empty($qualification)) {
                return $this->view(
                    "teacher/profile",
                    [
                        "title" => "My Profile",
                        "teacher" => $_SESSION['user'], // Pass current user data
                        "success" => null,
                        "error" => "Name and email are required."
                    ],
                    layout: "teacher"
                );
            }

            // Fetch the current user
            $userId = $_SESSION['user']['id'];
            $teacherModel = new Teacher();
            $user = $teacherModel->findByUserId($userId);
            // Update user data
            $teacherModel->update($userId, [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'qualification' => $qualification,
            ]);

            // Update session data
            $_SESSION['user']['name'] = $name;
            $_SESSION['user']['email'] = $email;
            $_SESSION['user']['phone'] = $phone;
            $_SESSION['user']['qualification'] = $qualification;
            // Redirect with success message
            header("Location: /teacher/profile?success=Profile updated successfully.");
            exit;
        }
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

        print_r($_POST);
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

        // Check the user's role from the session
        $userRole = $_SESSION['user']['role'] ?? null;

        // Redirect based on the user's role
        if ($userRole === 'admin') {
            header("Location: /teachers?updated=1");
        } elseif ($userRole === 'teacher') {
            header("Location: /teacher/profile?updated=1");
        }

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

    public function changePassword()
    {
        // Check if the form is submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            // Validate input
            if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
                return $this->view(
                    "teacher/change-password",
                    [
                        "title" => "Change Password",
                        "error" => "All fields are required."
                    ],
                    layout: "teacher"
                );
            }

            if ($newPassword !== $confirmPassword) {
                return $this->view(
                    "teacher/change-password",
                    [
                        "title" => "Change Password",
                        "error" => "New password and confirm password do not match."
                    ],
                    layout: "teacher"
                );
            }
            // Fetch the current user
            $userId = $_SESSION['user']['id'];
            $userModel = new User();
            $user = $userModel->findById($userId);

            // Verify current password
            if (!password_verify($currentPassword, $user['password'])) {
                return $this->view(
                    "teacher/change-password",
                    [
                        "title" => "Change Password",
                        "error" => "Current password is incorrect."
                    ],
                    layout: "student"
                );
            }

            // Update password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $userModel->updatePassword($userId, $hashedPassword);

            // Redirect with success message
            header("Location: /teacher/dashboard?success=Password updated successfully.");
            exit;
        }

        // Render the change password form
        return $this->view(
            "teacher/change-password",
            [
                "title" => "Change Password"
            ],
            layout: "teacher"
        );
    }
}
