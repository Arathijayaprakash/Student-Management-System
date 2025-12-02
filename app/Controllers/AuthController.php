<?php

namespace App\Controllers;

use App\Core\Controller;

class AuthController extends Controller
{
    public function loginPage()
    {
        return $this->view("auth/login", []);
    }
}
