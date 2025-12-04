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
        $students = $studentModel->getAll();

        return $this->view(
            "admin/student/studentList",
            [
                "title" => "Student List",
                "students" => $students
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
}
