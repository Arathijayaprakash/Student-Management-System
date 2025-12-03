<?php

namespace App\Controllers;

use App\Models\User;
use App\Core\Controller;

class AuthController extends Controller
{
    public function loginPage()
    {
        // If already logged in, redirect to their dashboard
        session_start();
        if (!empty($_SESSION['user'])) {
            $this->redirectToRole($_SESSION['user']['role']);
            return;
        }
        return $this->view("auth/login", []);
    }
    public function login()
    {
        session_start();

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        // Basic validation
        if ($username === '' || $password === '') {
            return $this->view('auth/login', ['error' => 'Username and password are required.']);
        }

        $userModel = new User();
        $user = $userModel->findByUsername($username);

        if (!$user) {
            return $this->view('auth/login', ['error' => 'Invalid credentials.']);
        }

        // Verify password
        if (!password_verify($password, $user['password'])) {
            return $this->view('auth/login', ['error' => 'Invalid credentials.']);
        }
        // Login success — set session
        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['name'],
            'role' => $user['role']
        ];

        // Redirect based on role
        $this->redirectToRole($user['role']);
    }

    public function logout()
    {
        session_start();
        session_destroy();

        header('Location: /login');
        exit;
    }
    private function redirectToRole(string $role)
    {
        if ($role === 'admin') {
            header('Location: /admin/dashboard');
            exit;
        }
        if ($role === 'teacher') {
            header('Location: /teacher/dashboard');
            exit;
        }
        // default student
        header('Location: /student/dashboard');
        exit;
    }
}
