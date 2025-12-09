<?php

namespace App\Core;

/**
 * Router Class
 * 
 * This class handles routing for the application. It allows defining routes for different HTTP methods
 * and resolves incoming requests to the appropriate callback or controller method.
 * 
 * @package App\Core
 */
class Router
{
    /**
     * @var array Holds all registered routes, grouped by HTTP method.
     */
    private array $routes = [];

    /**
     * Register a GET route.
     * 
     * @param string $path The route path (e.g., "/admin/dashboard").
     * @param callable|array $callback The callback or controller-method pair to handle the route.
     * 
     * @return void
     */
    public function get(string $path, callable|array $callback): void
    {
        $this->routes['GET'][$path] = $callback;
    }

    /**
     * Register a POST route.
     * 
     * @param string $path The route path (e.g., "/student/update").
     * @param callable|array $callback The callback or controller-method pair to handle the route.
     * 
     * @return void
     */
    public function post(string $path, callable|array $callback): void
    {
        $this->routes['POST'][$path] = $callback;
    }

    /**
     * Resolve the current request and execute the appropriate callback.
     * 
     * @return mixed The result of the callback execution.
     */
    public function resolve()
    {
        // Get the HTTP method and request path
        $method = $_SERVER['REQUEST_METHOD'];

        // Remove query string (?id=9)
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Find the callback for the route
        $callback = $this->routes[$method][$path] ?? false;

        if (!$callback) {
            // Handle 404 error
            http_response_code(404);
            echo "404 - Page Not Found";
            return null;
        }

        // If the callback is a controller-method pair
        if (is_array($callback)) {
            [$controller, $method] = $callback;

            // Ensure the controller class exists
            if (!class_exists($controller)) {
                throw new \Exception("Controller class '$controller' not found.");
            }

            // Ensure the method exists in the controller
            if (!method_exists($controller, $method)) {
                throw new \Exception("Method '$method' not found in controller '$controller'.");
            }

            // Instantiate the controller and call the method
            $controllerInstance = new $controller();
            return $controllerInstance->$method();
        }

        // If the callback is a closure or function
        if (is_callable($callback)) {
            return call_user_func($callback);
        }

        // If the callback is invalid
        throw new \Exception("Invalid route callback.");
    }
}
