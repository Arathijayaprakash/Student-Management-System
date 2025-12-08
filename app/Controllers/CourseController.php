<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Course;
use App\Services\PaginationService;

class CourseController extends Controller
{
    private $paginationService;

    public function __construct()
    {
        $this->paginationService = new PaginationService();
    }
    public function index()
    {
        $courseModel = new Course();
        //pagination variables
        $limit = 4; // Students per page
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

        //search filter
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        // Use PaginationService to get paginated students
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

    public function createPage()
    {
        return $this->view("admin/course/add", [], "admin");
    }

    public function store()
    {
        (new Course())->create($_POST);

        header("Location: /courses?created=1");
        exit;
    }

    public function editPage()
    {
        $course = (new Course())->findById($_GET['id']);

        return $this->view("admin/course/edit", [
            "course" => $course
        ], "admin");
    }

    public function update()
    {
        (new Course())->update($_POST['id'], $_POST);

        header("Location: /courses?updated=1");
        exit;
    }

    public function delete()
    {
        (new Course())->delete($_GET['id']);

        header("Location: /courses?deleted=1");
        exit;
    }
}
