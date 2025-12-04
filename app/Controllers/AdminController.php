<?php

namespace App\Controllers;

use App\Core\Controller;

class AdminController extends Controller
{
    public function dashboard()
    {
        return $this->view("admin/dashboard", [], "admin");
    }
}
