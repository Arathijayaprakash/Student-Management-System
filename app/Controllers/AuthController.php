<?php

namespace App\Controllers;

use App\Models\User;
use App\Core\Controller;

/**
 * Authentication Controller
 * 
 * Handles user authentication, including login, logout, and role-based redirection.
 */
class AuthController extends Controller
{
    /**
     * Display the login page.
     * Redirects logged-in users to their respective dashboards.
     * 
     * @return void
     */
    public function loginPage(): void
    {
        // Ensure the session is started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Redirect if already logged in
        if (!empty($_SESSION['user'])) {
            $this->redirectToRole($_SESSION['user']['role']);
            return;
        }

        // Render the login view
        $this->view("auth/login", []);
    }

    /**
     * Handle user login.
     * Validates credentials and redirects based on user role.
     * 
     * @return void
     */
    public function login(): void
    {
        // Ensure the session is started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        // Basic validation
        if (empty($username) || empty($password)) {
            $this->view('auth/login', ['error' => 'Username and password are required.']);
            return;
        }

        // Fetch user from the database
        $userModel = new User();
        $user = $userModel->findByUsername($username);

        // Check if user exists
        if (!$user) {
            $this->view('auth/login', ['error' => 'Invalid credentials.']);
            return;
        }

        // Verify password
        if (!password_verify($password, $user['password'])) {
            $this->view('auth/login', ['error' => 'Invalid credentials.']);
            return;
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

    /**
     * Handle user logout.
     * Destroys the session and redirects to the login page.
     * 
     * @return void
     */
    public function logout(): void
    {
        // Ensure the session is started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Destroy the session
        session_destroy();

        // Redirect to login page
        header('Location: /login');
        exit;
    }

    /**
     * Redirect user based on their role.
     * 
     * @param string $role The user's role.
     * 
     * @return void
     */
    private function redirectToRole(string $role): void
    {
        $redirectMap = [
            'admin' => '/admin/dashboard',
            'student' => '/student/dashboard',
            'teacher' => '/teacher/dashboard',
        ];

        // Redirect to the appropriate dashboard
        $redirectUrl = $redirectMap[$role] ?? '/login';
        header("Location: $redirectUrl");
        exit;
    }
}
