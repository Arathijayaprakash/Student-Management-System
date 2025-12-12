<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Course;
use App\Services\PaginationService;

/**
 * Course Controller
 * 
 * Handles course-related functionalities, including listing, creating, editing, updating, and deleting courses.
 */
class CourseController extends Controller
{
    /**
     * @var PaginationService
     */
    private PaginationService $paginationService;

    /**
     * Constructor
     * Initializes the PaginationService.
     */
    public function __construct()
    {
        $this->paginationService = new PaginationService();
    }

    /**
     * Display a paginated list of courses.
     * Supports search functionality.
     * 
     * @return void
     */
    public function index()
    {
        $courseModel = new Course();
        //pagination variables
        $limit = 4;
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

        //search filter
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        // Use PaginationService to get paginated courses
        $paginationData = $this->paginationService->paginate(
            fn($search, $limit, $offset) => $courseModel->getFilteredPaginated($search, $limit, $offset),
            fn($search) => $courseModel->countFiltered($search),
            $limit,
            $page,
            $search
        );

        return $this->view("admin/course/list", [
            "courses" => $paginationData['items'],
            "page" => $paginationData['page'],
            "totalPages" => $paginationData['totalPages'],
            "search" => $paginationData['search'],
        ], "admin");
    }

    /**
     * Display the course creation page.
     * 
     * @return void
     */
    public function createPage(): void
    {
        $this->view("admin/course/add", [], "admin");
    }

    /**
     * Handle the creation of a new course.
     * 
     * @return void
     */
    public function store(): void
    {
        (new Course())->create($_POST);

        header("Location: /course/list?created=1");
        exit;
    }

    /**
     * Display the course edit page.
     * 
     * @return void
     */
    public function editPage(): void
    {
        $courseId = $_GET['id'] ?? null;

        if (!$courseId) {
            $this->redirectWithMessage('/course/list', 'error=invalid_id');
            return;
        }

        $courseModel = new Course();
        $course = $courseModel->findById($courseId);

        if (!$course) {
            $this->redirectWithMessage('/course/list', 'error=not_found');
            return;
        }

        $this->view("admin/course/edit", [
            "course" => $course
        ], "admin");
    }

    /**
     * Handle the update of an existing course.
     * 
     * @return void
     */
    public function update(): void
    {
        $courseId = $_POST['id'] ?? null;

        if (!$courseId) {
            $this->redirectWithMessage('/course/list', 'error=invalid_id');
            return;
        }

        $courseModel = new Course();
        $courseModel->update($courseId, $_POST);

        $this->redirectWithMessage('/course/list', 'updated=1');
    }

    /**
     * Handle the deletion of a course.
     * 
     * @return void
     */
    public function delete(): void
    {
        $courseId = $_GET['id'] ?? null;

        if (!$courseId) {
            $this->redirectWithMessage('/course/list', 'error=invalid_id');
            return;
        }

        $courseModel = new Course();
        $courseModel->delete($courseId);

        $this->redirectWithMessage('/course/list', 'deleted=1');
    }
}
