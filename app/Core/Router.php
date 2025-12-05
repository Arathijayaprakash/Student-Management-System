<?php

namespace App\Core;

class Router
{
    private array $routes = [];
    public function get(string $path, $callback)
    {
        $this->routes['GET'][$path] = $callback;
    }
    public function post(string $path, $callback)
    {
        $this->routes['POST'][$path] = $callback;
    }

    public function resolve()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        // Remove query string (?id=9)
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

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
