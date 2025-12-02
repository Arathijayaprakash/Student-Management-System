<?php

namespace App\Core;

class Controller
{
    public function view(string $view, array $data)
    {
        extract($data);
        include __DIR__ . "/../Views/$view.php";
    }
}
