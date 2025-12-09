<?php

namespace App\Models;

use PDO;

/**
 * Course Model
 * 
 * Handles database operations related to courses, including creation, retrieval, updating, deletion, and filtering.
 */
class Course extends Model
{
    /**
     * Retrieve all courses.
     * 
     * @return array Returns an array of all courses ordered by ID in descending order.
     */
    public function getAll()
    {
        return $this->db->query("SELECT * FROM courses ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieve a paginated and filtered list of courses.
     * 
     * @param string $search Search term for filtering courses by name.
     * @param int $limit Number of records per page.
     * @param int $offset Offset for pagination.
     * @return array Returns an array of filtered and paginated course records.
     */
    public function getFilteredPaginated(string $search, int $limit, int $offset): array
    {
        $sql = "SELECT * FROM courses WHERE courses.course_name LIKE :search LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":search", '%' . $search . '%', \PDO::PARAM_STR);
        $stmt->bindValue(":limit", $limit, \PDO::PARAM_INT);
        $stmt->bindValue(":offset", $offset, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Count the total number of filtered course records.
     * 
     * @param string $search Search term for filtering courses by name.
     * @return int Returns the total count of filtered course records.
     */
    public function countFiltered(string $search): int
    {
        $sql = "SELECT COUNT(*) as total FROM courses WHERE courses.course_name LIKE :search";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":search", '%' . $search . '%', \PDO::PARAM_STR);
        $stmt->execute();

        return (int)$stmt->fetchColumn();
    }

    /**
     * Find a course by its ID.
     * 
     * @param int $id The ID of the course.
     * @return array|null Returns the course record as an associative array, or null if not found.
     */
    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM courses WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Create a new course record.
     * 
     * @param array $data Associative array containing 'name', 'description', and 'duration'.
     * @return bool Returns true on success, false on failure.
     */
    public function create($data)
    {
        print_r($data);
        $stmt = $this->db->prepare("INSERT INTO courses (course_name, description, duration) VALUES (:course_name, :description, :duration)");
        return $stmt->execute([
            'course_name' => $data['name'],
            'description' => $data['description'],
            'duration' => $data['duration']
        ]);
    }

    /**
     * Update a course record.
     * 
     * @param int $id The ID of the course to update.
     * @param array $data Associative array containing 'course_name', 'description', and 'duration'.
     * @return bool Returns true on success, false on failure.
     */
    public function update($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE courses SET course_name = :course_name, description = :description, duration = :duration WHERE id = :id");
        return $stmt->execute([
            'course_name' => $data['course_name'],
            'description' => $data['description'],
            'duration' => $data['duration'],
            'id' => $id
        ]);
    }

    /**
     * Delete a course record.
     * 
     * @param int $id The ID of the course to delete.
     * @return bool Returns true on success, false on failure.
     */
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM courses WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Count the total number of course records.
     * 
     * @return int Returns the total count of course records.
     */
    public function countAll(): int
    {
        return (int)$this->db->query("SELECT COUNT(*) FROM courses")->fetchColumn();
    }
}
