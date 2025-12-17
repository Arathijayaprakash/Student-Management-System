<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Student;

class StudentApiController
{
    /**
     * Handle the creation of a new student.
     * 
     * @return void
     */
    public function store(): void
    {
        // Set the content type to JSON
        header('Content-Type: application/json');

        // Get the JSON input data
        $data = json_decode(file_get_contents('php://input'), true);

        // Validate input data
        if (!isset($data['name'], $data['email'], $data['password'], $data['course_id'])) {
            http_response_code(400); // Bad Request
            echo json_encode(['status' => 'error', 'message' => 'Invalid input. Required fields: name, email, password, course_id']);
            return;
        }

        $userModel = new User();
        $studentModel = new Student();

        // Auto-generate hashed password
        $plainPassword = $data['password'];
        $hashedPassword = password_hash($plainPassword, PASSWORD_BCRYPT);

        // Insert into users table
        $userId = $userModel->create([
            "name" => $data['name'],
            "email" => $data['email'],
            "password" => $hashedPassword,
            "role" => "student"
        ]);

        if (!$userId) {
            http_response_code(500); // Internal Server Error
            echo json_encode(['status' => 'error', 'message' => 'Failed to create user']);
            return;
        }

        // Handle image upload (optional)
        $photoName = $data['photo'] ?? null;

        // Insert into students table
        $result = $studentModel->create([
            "user_id" => $userId,
            "course_id" => $data['course_id'],
            "photo" => $photoName
        ]);

        if ($result) {
            http_response_code(201); // Created
            echo json_encode([
                'status' => 'success',
                'message' => 'Student created successfully',
                'data' => [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'course_id' => $data['course_id'],
                    'photo' => $photoName,
                    'password' => $plainPassword // Return plain password for reference
                ]
            ]);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['status' => 'error', 'message' => 'Failed to create student']);
        }
    }

    /**
     * Handle fetching a single student by ID.
     * 
     * @param int $id Student ID passed via the URL.
     * @return void
     */
    public function show(int $id): void
    {
        // Set the content type to JSON
        header('Content-Type: application/json');

        // Validate the ID
        if ($id <= 0) {
            http_response_code(400); // Bad Request
            echo json_encode(['status' => 'error', 'message' => 'Invalid student ID']);
            return;
        }

        $studentModel = new Student();

        // Fetch the student by ID
        $student = $studentModel->findById($id);

        if ($student) {
            // Return the student data
            http_response_code(200); // OK
            echo json_encode([
                'status' => 'success',
                'data' => $student
            ]);
        } else {
            // Student not found
            http_response_code(404); // Not Found
            echo json_encode(['status' => 'error', 'message' => 'Student not found']);
        }
    }

    /**
     * Handle updating a student's information.
     * 
     * @param int $id Student ID passed via the URL.
     * @return void
     */
    public function update(int $id): void
    {
        // Set the content type to JSON
        header('Content-Type: application/json');

        // Get the JSON input data
        $data = json_decode(file_get_contents('php://input'), true);

        // Validate the ID
        if ($id <= 0) {
            http_response_code(400); // Bad Request
            echo json_encode(['status' => 'error', 'message' => 'Invalid student ID']);
            return;
        }

        // Validate input data
        if (!isset($data['name'], $data['email'], $data['course_id'])) {
            http_response_code(400); // Bad Request
            echo json_encode(['status' => 'error', 'message' => 'Invalid input. Required fields: name, email, course_id']);
            return;
        }
        $studentModel = new Student();
        $userModel = new User();

        // Fetch the student by ID
        $student = $studentModel->findById($id);

        if (!$student) {
            // Student not found
            http_response_code(404); // Not Found
            echo json_encode(['status' => 'error', 'message' => 'Student not found']);
        }
        // Update the user table
        $userUpdateResult = $userModel->update($student['user_id'], [
            "name" => $data['name'],
            "email" => $data['email']
        ]);

        if (!$userUpdateResult) {
            http_response_code(500); // Internal Server Error
            echo json_encode(['status' => 'error', 'message' => 'Failed to update user information']);
            return;
        }
        // Handle image upload (optional)
        $photoName = $data['photo'] ?? $student['photo'];

        // Update the student table
        $studentUpdateResult = $studentModel->update($id, [
            "course_id" => $data['course_id'],
            "photo" => $photoName
        ]);

        if ($studentUpdateResult) {
            http_response_code(200); // OK
            echo json_encode([
                'status' => 'success',
                'message' => 'Student updated successfully',
                'data' => [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'course_id' => $data['course_id'],
                    'photo' => $photoName
                ]
            ]);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['status' => 'error', 'message' => 'Failed to update student information']);
        }
    }

    /**
     * Handle deleting a student by ID.
     * 
     * @param int $id Student ID passed via the URL.
     * @return void
     */
    public function delete(int $id): void
    {
        // Set the content type to JSON
        header('Content-Type: application/json');

        // Validate the ID
        if ($id <= 0) {
            http_response_code(400); // Bad Request
            echo json_encode(['status' => 'error', 'message' => 'Invalid student ID']);
            return;
        }
        $studentModel = new Student();
        $student = $studentModel->findById($id);

        if (!$student) {
            // Student not found
            http_response_code(404); // Not Found
            echo json_encode(['status' => 'error', 'message' => 'Student not found']);
            return;
        }
        $userId = $student['user_id'];
        // Delete the student record
        $studentDeleted =  (new User())->delete($userId);

        if ($studentDeleted) {
            http_response_code(200); // OK
            echo json_encode(['status' => 'success', 'message' => 'Student deleted successfully']);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete associated user']);
        }
    }
}
