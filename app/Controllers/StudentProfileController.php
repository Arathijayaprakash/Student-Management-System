<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Student;
use App\Models\User;

/**
 * Student Controller
 * 
 * Handles student-related functionalities, including listing, creating, editing, updating, and deleting students.
 */
class StudentProfileController extends Controller
{
    /**
     * Display the student dashboard.
     * 
     * @return void
     */
    public function dashboard()
    {
        return $this->view("student/dashboard", [], "student");
    }
    /**
     * Display the student's profile.
     * 
     * @return void
     */
    public function viewProfile()
    {
        // Get logged-in student ID from session
        $userId = $_SESSION['user']['id'];
        // Fetch student details
        $student = (new Student())->findByUserId($userId);

        return $this->view(
            "student/profile",
            [
                "title" => "My Profile",
                "student" => $student
            ],
            layout: "student"
        );
    }
    /**
     * Handle profile update.
     * 
     * @return void
     */
    public function updateProfile()
    {
        // Check if the form is submitted via POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $photo = $_FILES['photo'] ?? null;

            // Validate input
            if (empty($name) || empty($email)) {
                return $this->view(
                    "student/profile",
                    [
                        "title" => "My Profile",
                        "student" => $_SESSION['user'], // Pass current user data
                        "success" => null,
                        "error" => "Name and email are required."
                    ],
                    layout: "student"
                );
            }

            // Fetch the current user
            $userId = $_SESSION['user']['id'];
            $studentModel = new Student();
            $user = $studentModel->findByUserId($userId);
            // Handle photo upload
            $photoName = $this->handleImageUpload($photo, $user['photo']);


            // Update user data
            $studentModel->updateProfile($userId, [
                'name' => $name,
                'email' => $email,
                'photo' => $photoName
            ]);

            // Update session data
            $_SESSION['user']['name'] = $name;
            $_SESSION['user']['email'] = $email;
            $_SESSION['user']['photo'] = $photoName;

            $this->redirectWithMessage('/student/profile', 'success=Profile updated successfully.');
        }
    }
    public function courses()
    {
        // Get logged-in user ID from session
        $userId = $_SESSION['user']['id'];

        // Fetch courses for the student
        $courses = (new Student())->getCoursesByUserId($userId);

        // Render the course page
        return $this->view(
            "student/course",
            [
                "title" => "My Courses",
                "courses" => $courses
            ],
            layout: "student"
        );
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
                    "student/change_password",
                    [
                        "title" => "Change Password",
                        "error" => "All fields are required."
                    ],
                    layout: "student"
                );
            }

            if ($newPassword !== $confirmPassword) {
                return $this->view(
                    "student/change_password",
                    [
                        "title" => "Change Password",
                        "error" => "New password and confirm password do not match."
                    ],
                    layout: "student"
                );
            }
            // Fetch the current user
            $userId = $_SESSION['user']['id'];
            $userModel = new User();
            $user = $userModel->findById($userId);

            // Verify current password
            if (!password_verify($currentPassword, $user['password'])) {
                return $this->view(
                    "student/change_password",
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

            $this->redirectWithMessage('/student/dashboard', 'success=Password updated successfully.');
        }


        // Render the change password form
        return $this->view(
            "student/change_password",
            [
                "title" => "Change Password"
            ],
            layout: "student"
        );
    }
}
