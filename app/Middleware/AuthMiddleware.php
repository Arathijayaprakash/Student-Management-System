<?php
namespace App\Middleware;

class AuthMiddleware
{
    public static function check()
    {
        session_start();
        if (empty($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }
    }

    public static function checkRole(array $roles = [])
    {
        session_start();
        if (empty($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }
        if (!in_array($_SESSION['user']['role'], $roles, true)) {
            http_response_code(403);
            echo 'Forbidden';
            exit;
        }
    }
}
