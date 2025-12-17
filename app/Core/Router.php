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
     * Register a PUT route.
     * 
     * @param string $path The route path (e.g., "/api/students/{id}").
     * @param callable|array $callback The callback or controller-method pair to handle the route.
     * 
     * @return void
     */
    public function put(string $path, callable|array $callback): void
    {
        $this->routes['PUT'][$path] = $callback;
    }

    /**
     * Register a DELETE route.
     * 
     * @param string $path The route path (e.g., "/api/students/{id}").
     * @param callable|array $callback The callback or controller-method pair to handle the route.
     * 
     * @return void
     */
    public function delete(string $path, callable|array $callback): void
    {
        $this->routes['DELETE'][$path] = $callback;
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
        $routeMatch = $this->matchRoute($method, $path);

        if (!$routeMatch) {
            // Handle 404 error
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => '404 - Page Not Found']);
            return null;
        }

        // Extract the callback and parameters
        $callback = $routeMatch['callback'];
        $params = $routeMatch['params'];

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

            // Instantiate the controller and call the method, passing route parameters
            $controllerInstance = new $controller();
            return $controllerInstance->$method(...$params);
        }

        // If the callback is a closure or function
        if (is_callable($callback)) {
            return call_user_func_array($callback, $params);
        }

        // If the callback is invalid
        throw new \Exception("Invalid route callback.");
    }

    /**
     * Match the current route with registered routes, including dynamic parameters.
     * 
     * @param string $method The HTTP method (e.g., GET, POST).
     * @param string $path The request path (e.g., "/api/students/23").
     * 
     * @return array|false The matched callback and parameters, or false if no match is found.
     */
    private function matchRoute(string $method, string $path)
    {
        // Check if the method has registered routes
        if (!isset($this->routes[$method])) {
            return false;
        }

        // Loop through all registered routes for the method
        foreach ($this->routes[$method] as $route => $callback) {
            // Convert route to a regex pattern
            $routePattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_]+)', $route);
            $routePattern = "#^" . $routePattern . "$#";

            // Check if the path matches the route pattern
            if (preg_match($routePattern, $path, $matches)) {
                // Remove the full match from the matches array
                array_shift($matches);

                // Return the callback and extracted parameters
                return [
                    'callback' => $callback,
                    'params' => $matches
                ];
            }
        }

        // No match found
        return false;
    }
}
