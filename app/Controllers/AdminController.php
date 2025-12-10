<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Student;
use App\Models\Course;
use App\Models\Teacher;

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

    public function assignCoursesPage()
    {
        $id = $_GET['id'];
        $courses = (new Course())->getAll();
        $teacherModel = new Teacher();
        $teacher = $teacherModel->findById($id);
        return $this->view("admin/assign-courses", [
            "teacher" => $teacher,
            "courses" => $courses,
        ], "admin");
    }

    /**
     * Handle the assignment of courses to a teacher.
     * 
     * @return void
     */
    public function assignCourses()
    {
        // Validate input
        $teacherId = $_POST['teacher_id'] ?? null;
        $courseIds = $_POST['course_ids'] ?? [];

        // Assign courses using the Teacher model
        $teacherModel = new Teacher();
        $teacherModel->assignCourses((int)$teacherId, $courseIds);

        header("Location: /teachers?assigned-course");
        exit;
    }
}
