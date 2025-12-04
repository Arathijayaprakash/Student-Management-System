<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class Student
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO students (user_id, course, photo)
            VALUES (:user_id, :course, :photo)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ":user_id" => $data['user_id'],
            ":course"  => $data['course'],
            ":photo"   => $data['photo'] ?? null
        ]);
    }


    public function getAll(): array
    {
        $sql = "SELECT s.id, u.name, u.email, u.role, s.photo, s.course, s.created_at
                FROM students s
                JOIN users u ON u.id = s.user_id";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}
