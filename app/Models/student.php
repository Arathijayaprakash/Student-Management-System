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

    public function findById(int $id): ?array
    {
        $sql = "SELECT s.id, s.user_id, s.photo, s.course, s.created_at,
                   u.name, u.email, u.role
            FROM students s
            JOIN users u ON u.id = s.user_id
            WHERE s.id = :id
            LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function update($id, array $data)
    {
        $sql = "UPDATE students SET course = :course, photo = :photo WHERE id = :id";
        $data['id'] = $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function getFilteredPaginated(string $search, int $limit, int $offset): array
    {
        $sql = "SELECT s.id, u.name, u.email, s.photo, s.course
            FROM students s
            JOIN users u ON u.id = s.user_id
            WHERE u.name LIKE :search OR u.email LIKE :search OR s.course LIKE :search
            ORDER BY s.id DESC
            LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":search", '%' . $search . '%', \PDO::PARAM_STR);
        $stmt->bindValue(":limit", $limit, \PDO::PARAM_INT);
        $stmt->bindValue(":offset", $offset, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function countFiltered(string $search): int
    {
        $sql = "SELECT COUNT(*)
            FROM students s
            JOIN users u ON u.id = s.user_id
            WHERE u.name LIKE :search OR u.email LIKE :search OR s.course LIKE :search";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":search", '%' . $search . '%', \PDO::PARAM_STR);
        $stmt->execute();

        return (int)$stmt->fetchColumn();
    }

    public function countAll(): int
    {
        return (int)$this->db->query("SELECT COUNT(*) FROM students")->fetchColumn();
    }
}
