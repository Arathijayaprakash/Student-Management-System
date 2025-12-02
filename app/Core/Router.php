<?php

namespace App\Core;

class Router
{
    private array $routes = [];
    public function get(string $path, $callback)
    {
        $this->routes['GET'][$path] = $callback;
    }
    public function resolve()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = $_SERVER['REQUEST_URI'];
        $callback = $this->routes[$method][$path] ?? false;
        if (!$callback) {
            http_response_code(404);
            echo "Page Not Found";
            exit;
        }
        if (is_array($callback)) {
            $controller = new $callback[0];
            $method = $callback[1];
            return $controller->$method();
        }
        return call_user_func($callback);
    }
}
