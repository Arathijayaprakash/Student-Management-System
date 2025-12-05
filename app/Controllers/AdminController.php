<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Student;

class AdminController extends Controller
{
    public function dashboard()
    {
        $studentModel = new Student();
        $studentCount = $studentModel->countAll();
        return $this->view("admin/dashboard", [
            "studentCount" => $studentCount,
        ], "admin");
    }
}
