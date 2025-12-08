<?php

namespace App\Models;

use PDO;

class Course extends Model
{
    public function getAll()
    {
        return $this->db->query("SELECT * FROM courses ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
    }
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

    public function countFiltered(string $search): int
    {
        $sql = "SELECT COUNT(*) as total FROM courses WHERE courses.course_name LIKE :search";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":search", '%' . $search . '%', \PDO::PARAM_STR);
        $stmt->execute();

        return (int)$stmt->fetchColumn();
    }
    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM courses WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

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

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE courses SET course_name = :name, description = :description WHERE id = :id");
        return $stmt->execute([
            'name' => $data['course_name'],
            'description' => $data['description'],
            'id' => $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM courses WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
