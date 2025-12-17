<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\User;
use App\Models\Student;

class StudentApiController
{
    /**
     * Handle fetching all students.
     *
     * @return void
     */
    public function index(): void
    {
        $this->setJsonHeader();

        $studentModel = new Student();
        $students = $studentModel->getAll();

        if (!empty($students)) {
            $this->sendResponse(200, 'success', $students);
        } else {
            $this->sendResponse(404, 'error', 'No students found');
        }
    }

    /**
     * Handle the creation of a new student.
     *
     * @return void
     */
    public function store(): void
    {
        $this->setJsonHeader();

        $data = $this->getJsonInput();

        if (!$this->validateInput($data, ['name', 'email', 'password', 'course_id'])) {
            $this->sendResponse(400, 'error', 'Invalid input. Required fields: name, email, password, course_id');
            return;
        }

        $userModel = new User();
        $studentModel = new Student();

        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);

        // Insert into users table
        $userId = $userModel->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $hashedPassword,
            'role' => 'student',
        ]);

        if (!$userId) {
            $this->sendResponse(500, 'error', 'Failed to create user');
            return;
        }

        // Handle optional photo
        $photoName = $data['photo'] ?? null;

        // Insert into students table
        $result = $studentModel->create([
            'user_id' => $userId,
            'course_id' => $data['course_id'],
            'photo' => $photoName,
        ]);

        if ($result) {
            $this->sendResponse(201, 'success', [
                'name' => $data['name'],
                'email' => $data['email'],
                'course_id' => $data['course_id'],
                'photo' => $photoName,
            ]);
        } else {
            $this->sendResponse(500, 'error', 'Failed to create student');
        }
    }

    /**
     * Handle fetching a single student by ID.
     *
     * @param int $id
     * @return void
     */
    public function show(int $id): void
    {
        $this->setJsonHeader();

        if ($id <= 0) {
            $this->sendResponse(400, 'error', 'Invalid student ID');
            return;
        }

        $studentModel = new Student();
        $student = $studentModel->findById($id);

        if ($student) {
            $this->sendResponse(200, 'success', $student);
        } else {
            $this->sendResponse(404, 'error', 'Student not found');
        }
    }

    /**
     * Handle updating a student's information.
     *
     * @param int $id
     * @return void
     */
    public function update(int $id): void
    {
        $this->setJsonHeader();

        $data = $this->getJsonInput();

        if ($id <= 0) {
            $this->sendResponse(400, 'error', 'Invalid student ID');
            return;
        }

        if (!$this->validateInput($data, ['name', 'email', 'course_id'])) {
            $this->sendResponse(400, 'error', 'Invalid input. Required fields: name, email, course_id');
            return;
        }

        $studentModel = new Student();
        $userModel = new User();

        $student = $studentModel->findById($id);

        if (!$student) {
            $this->sendResponse(404, 'error', 'Student not found');
            return;
        }

        // Update user table
        $userUpdateResult = $userModel->update($student['user_id'], [
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        if (!$userUpdateResult) {
            $this->sendResponse(500, 'error', 'Failed to update user information');
            return;
        }

        // Handle optional photo
        $photoName = $data['photo'] ?? $student['photo'];

        // Update student table
        $studentUpdateResult = $studentModel->update($id, [
            'course_id' => $data['course_id'],
            'photo' => $photoName,
        ]);

        if ($studentUpdateResult) {
            $this->sendResponse(200, 'success', [
                'name' => $data['name'],
                'email' => $data['email'],
                'course_id' => $data['course_id'],
                'photo' => $photoName,
            ]);
        } else {
            $this->sendResponse(500, 'error', 'Failed to update student information');
        }
    }

    /**
     * Handle deleting a student by ID.
     *
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        $this->setJsonHeader();

        if ($id <= 0) {
            $this->sendResponse(400, 'error', 'Invalid student ID');
            return;
        }

        $studentModel = new Student();
        $student = $studentModel->findById($id);

        if (!$student) {
            $this->sendResponse(404, 'error', 'Student not found');
            return;
        }

        $userId = $student['user_id'];
        $studentDeleted = (new User())->delete($userId);

        if ($studentDeleted) {
            $this->sendResponse(200, 'success', 'Student deleted successfully');
        } else {
            $this->sendResponse(500, 'error', 'Failed to delete associated user');
        }
    }

    /**
     * Set the content type to JSON.
     *
     * @return void
     */
    private function setJsonHeader(): void
    {
        header('Content-Type: application/json');
    }

    /**
     * Get JSON input data.
     *
     * @return array
     */
    private function getJsonInput(): array
    {
        return json_decode(file_get_contents('php://input'), true) ?? [];
    }

    /**
     * Validate input data for required fields.
     *
     * @param array $data
     * @param array $requiredFields
     * @return bool
     */
    private function validateInput(array $data, array $requiredFields): bool
    {
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                return false;
            }
        }
        return true;
    }

    /**
     * Send a JSON response.
     *
     * @param int $statusCode
     * @param string $status
     * @param mixed $message
     * @return void
     */
    private function sendResponse(int $statusCode, string $status, $message): void
    {
        http_response_code($statusCode);
        echo json_encode(['status' => $status, 'message' => $message]);
    }
}
