<?php

namespace App\Core;

/**
 * Base Controller Class
 * 
 * This class provides common functionality for all controllers in the application.
 * It includes a method to render views and optionally wrap them in a layout.
 * 
 * @package App\Core
 */
class Controller
{
    /**
     * Render a view with optional layout.
     * 
     * This method renders a specified view file, passes data to it, and optionally wraps it
     * in a layout file. The view file's output is captured and inserted into the layout.
     * 
     * @param string $view   The name of the view file to render (e.g., "admin", "dashboard").
     * @param array  $data   An associative array of data to pass to the view. Default is an empty array.
     * @param string $layout The name of the layout file to use. Default is "main".
     * 
     * @return void
     */
    public function view(string $view, array $data = [], string $layout = "main")
    {
        // Extract data array into variables for use in the view
        extract($data);

        // Start output buffering and include the view file
        ob_start();
        include __DIR__ . "/../Views/$view.php";
        $content = ob_get_clean();

        // Include the layout file, which will use the $content variable
        include __DIR__ . "/../Views/layouts/$layout.php";
    }
}
