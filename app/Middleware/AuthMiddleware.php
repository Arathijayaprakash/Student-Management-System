<?php

namespace App\Middleware;

/**
 * Authentication Middleware
 * 
 * This class provides methods to enforce authentication and role-based access control.
 * It ensures that only authenticated users with the appropriate roles can access certain routes.
 * 
 * @package App\Middleware
 */
class AuthMiddleware
{
    /**
     * Check if the user is authenticated.
     * 
     * This method ensures that a user is logged in by checking the session. If the user is not
     * authenticated, they are redirected to the login page.
     * 
     * @param string $loginPath The path to the login page. Default is "/login".
     * 
     * @return void
     */
    public static function check(string $loginPath = '/login'): void
    {
        // Ensure the session is started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Check if the user is logged in
        if (empty($_SESSION['user'])) {
            header("Location: $loginPath");
            exit;
        }
    }

    /**
     * Check if the user has one of the allowed roles.
     * 
     * This method ensures that the logged-in user has one of the specified roles. If the user
     * is not authenticated or does not have the required role, appropriate actions are taken.
     * 
     * @param array $roles     An array of allowed roles.
     * @param string $loginPath The path to the login page. Default is "/login".
     * 
     * @return void
     */
    public static function checkRole(array $roles = [], string $loginPath = '/login'): void
    {
        // Ensure the session is started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Check if the user is logged in
        if (empty($_SESSION['user'])) {
            header("Location: $loginPath");
            exit;
        }

        // Check if the user's role is in the allowed roles
        $userRole = $_SESSION['user']['role'] ?? null;
        if (!in_array($userRole, $roles, true)) {
            http_response_code(403);
            echo '403 Forbidden - You do not have permission to access this resource.';
            exit;
        }
    }
}
