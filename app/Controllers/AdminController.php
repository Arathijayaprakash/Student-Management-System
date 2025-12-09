<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Student;
use App\Models\Course;

/**
 * Admin Controller
 * 
 * Handles admin-specific functionalities, such as displaying the dashboard.
 */
class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     * Fetches the total count of students and courses and passes them to the view.
     * 
     * @return void
     */
    public function dashboard()
    {
        $studentCount = (new Student())->countAll();
        $courseCount = (new Course())->countAll();
        return $this->view("admin/dashboard", [
            "studentCount" => $studentCount,
            "courseCount" => $courseCount,
        ], "admin");
    }
}
