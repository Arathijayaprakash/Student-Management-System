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

    /**
     * Handle image upload.
     * 
     * This method handles the uploading of an image file. It validates the file type,
     * generates a unique file name, and moves the file to the specified upload directory.
     * 
     * @param array|null $file The uploaded file (from $_FILES).
     * @param string|null $existingPhoto The existing photo name (if any).
     * 
     * @return string|null The uploaded photo name or the existing photo name.
     */
    protected function handleImageUpload(?array $file, ?string $existingPhoto = null): ?string
    {
        if (!$file || empty($file['name'])) {
            return $existingPhoto; // Return existing photo if no new file is uploaded
        }

        $uploadDir = __DIR__ . '/../../public/uploads/students/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // Create the directory if it doesn't exist
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

        // Validate file extension
        if (!in_array(strtolower($ext), $allowedExtensions)) {
            throw new \Exception("Invalid file type. Only JPG, JPEG, PNG, and WEBP are allowed.");
        }

        // Generate a unique file name
        $photoName = uniqid('img_') . '.' . $ext;
        $targetPath = $uploadDir . $photoName;

        // Move the uploaded file to the target directory
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            throw new \Exception("Failed to upload the file.");
        }

        return $photoName;
    }

    /**
     * Redirect to a URL with a query string message.
     * 
     * This method redirects the user to a specified URL and appends a query string message.
     * 
     * @param string $url     The URL to redirect to.
     * @param string $message The query string message.
     * 
     * @return void
     */
    protected function redirectWithMessage(string $url, string $message): void
    {
        header("Location: {$url}?{$message}");
        exit;
    }
}
