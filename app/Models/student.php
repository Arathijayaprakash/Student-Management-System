<?php

namespace App\Models;

use PDO;

class Student extends Model
{
    public function create(array $data): bool
    {
        $sql = "INSERT INTO students (user_id, course_id, photo)
            VALUES (:user_id, :course_id, :photo)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ":user_id" => $data['user_id'],
            ":course_id"  => $data['course_id'],
            ":photo"   => $data['photo'] ?? null
        ]);
    }


    public function getAll(): array
    {
        $sql = "SELECT s.id, u.name, u.email, u.role, s.photo, s.created_at, c.course_name AS course
                FROM students s
                JOIN users u ON u.id = s.user_id
                LEFT JOIN courses c ON c.id = s.course_id";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $sql = "SELECT s.id, s.user_id, s.photo, c.course_name, c.id as course_id, s.created_at,
                   u.name, u.email, u.role
            FROM students s
            JOIN users u ON u.id = s.user_id
            LEFT JOIN courses c ON c.id = s.course_id
            WHERE s.id = :id
            LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
    public function findByUserId(int $userId): ?array
    {
        $sql = "SELECT s.id, s.user_id, s.photo, c.course_name, c.id as course_id, s.created_at,
                   u.name, u.email, u.role
            FROM students s
            JOIN users u ON u.id = s.user_id
            LEFT JOIN courses c ON c.id = s.course_id
            WHERE s.user_id = :user_id
            LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
    public function getCoursesByUserId(int $userId): array
    {
        $sql = "SELECT 
                c.id AS course_id, 
                c.course_name, 
                c.description, 
                s.created_at AS enrollment_date
            FROM students s
            JOIN courses c ON s.course_id = c.id
            WHERE s.user_id = :user_id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function update($id, array $data)
    {
        $sql = "UPDATE students SET course_id = :course_id, photo = :photo WHERE id = :id";
        $data['id'] = $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function getFilteredPaginated(string $search, int $limit, int $offset): array
    {
        $sql = "
        SELECT 
            students.id,
            students.photo,
            users.name,
            users.email,
            courses.course_name AS course
        FROM students
        JOIN users ON students.user_id = users.id
        LEFT JOIN courses ON students.course_id = courses.id
        WHERE users.name LIKE :search 
           OR users.email LIKE :search 
           OR courses.course_name LIKE :search
        LIMIT :limit OFFSET :offset
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":search", '%' . $search . '%', \PDO::PARAM_STR);
        $stmt->bindValue(":limit", $limit, \PDO::PARAM_INT);
        $stmt->bindValue(":offset", $offset, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function countFiltered(string $search): int
    {
        $sql = "
        SELECT COUNT(*) as total
        FROM students
        JOIN users ON students.user_id = users.id
        LEFT JOIN courses ON students.course_id = courses.id
        WHERE users.name LIKE :search 
           OR users.email LIKE :search 
           OR courses.course_name LIKE :search
    ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":search", '%' . $search . '%', \PDO::PARAM_STR);
        $stmt->execute();

        return (int)$stmt->fetchColumn();
    }

    public function countAll(): int
    {
        return (int)$this->db->query("SELECT COUNT(*) FROM students")->fetchColumn();
    }

    public function updateProfile(int $userId, array $data): bool
    {
        try {
            // Begin transaction
            $this->db->beginTransaction();

            // Update the users table
            $sqlUsers = "UPDATE users SET name = :name, email = :email WHERE id = :id";
            $stmtUsers = $this->db->prepare($sqlUsers);
            $stmtUsers->execute([
                ':name' => $data['name'],
                ':email' => $data['email'],
                ':id' => $userId
            ]);

            // Update the students table
            $sqlStudents = "UPDATE students SET photo = :photo WHERE user_id = :user_id";
            $stmtStudents = $this->db->prepare($sqlStudents);
            $stmtStudents->execute([
                ':photo' => $data['photo'],
                ':user_id' => $userId
            ]);

            // Commit transaction
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            // Rollback transaction on error
            $this->db->rollBack();
            return false;
        }
    }
}
