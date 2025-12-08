<?php

namespace App\Core;

class Controller
{
    public function view(string $view, array $data = [], string $layout = "main")
    {
        extract($data);
        ob_start();
        include __DIR__ . "/../Views/$view.php";
        $content = ob_get_clean();

        include __DIR__ . "/../Views/layouts/$layout.php";
    }
}
