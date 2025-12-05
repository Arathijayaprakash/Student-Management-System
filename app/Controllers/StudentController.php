<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Student;
use App\Models\User;

class StudentController extends Controller
{
    public function index()
    {
        $studentModel = new Student();

        //pagination variables
        $limit = 4; // Students per page
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $offset = ($page - 1) * $limit;

        //search filter
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        // Fetch total students based on search
        $totalStudents = $studentModel->countFiltered($search);

        // Calculate total pages
        $totalPages = ceil($totalStudents / $limit);

        // Fetch paginated and filtered students
        $students = $studentModel->getFilteredPaginated($search, $limit, $offset);
        return $this->view(
            "admin/student/studentList",
            [
                "title" => "Student List",
                "students" => $students,
                "page" => $page,
                "totalPages" => $totalPages,
                "search" => $search,
            ],
            layout: "admin"
        );
    }


    public function createPage()
    {
        return $this->view(
            "admin/student/add",
            ["title" => "Add Student"],
            layout: "admin"
        );
    }

    public function store()
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
            return $this->view("admin/student/add", [
                "error" => "Failed to create user"
            ], "admin");
        }

        // -------------------------
        // Image Upload Handling
        // -------------------------

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
                return $this->view("admin/student/add", [
                    "error" => "Only JPG, PNG, WEBP images allowed"
                ], "admin");
            }

            // Generate unique file name
            $photoName = uniqid('stu_') . '.' . $ext;

            $targetPath = $uploadDir . $photoName;

            move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath);
        }


        // 2️⃣ Insert into students table
        $studentModel->create([
            "user_id" => $userId,
            "course"  => $_POST["course"],
            "photo"   => $photoName

        ]);
        $students = $studentModel->getAll();

        return $this->view(
            "admin/student/studentList",
            [
                "title"    => "Student List",
                "students" => $students,
                "success"  => "Student created successfully!",
                "password" => $plainPassword
            ],
            layout: "admin"
        );
    }

    // SHOW EDIT PAGE
    public function editPage()
    {
        $id = $_GET['id'];
        $student = (new Student())->findById($id);
        return $this->view(
            "admin/student/edit",
            [
                "title" => "Edit Student",
                "student" => $student
            ],
            layout: "admin"
        );
    }

    // UPDATE STUDENT
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
            "course" => $_POST['course'],
            "photo"  => $photoName
        ]);

        header("Location: /student?updated=1");
        exit;
        exit;
    }

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
