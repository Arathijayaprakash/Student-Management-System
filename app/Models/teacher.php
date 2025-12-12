<?php

namespace App\Models;

use PDO;

/**
 * Teacher Model
 * 
 * Handles database operations related to students, including creation, retrieval, updating, and filtering.
 */
class Teacher extends Model
{
    /**
     * Create a new teacher record.
     * 
     * @param array $data Associative array containing 'user_id', 'course_id', and optional 'photo'.
     * @return bool Returns true on success, false on failure.
     */
    public function create(array $data): bool
    {
        $sql = "INSERT INTO teachers (user_id, phone, qualification)
            VALUES (:user_id, :phone, :qualification)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ":user_id" => $data['user_id'],
            ":phone"  => $data['phone'],
            ":qualification"   => $data['qualification'] ?? null
        ]);
    }

    /**
     * Retrieve a paginated and filtered list of teachers.
     * 
     * @param string $search Search term for filtering teachers.
     * @param int $limit Number of records per page.
     * @param int $offset Offset for pagination.
     * @return array Returns an array of filtered and paginated teacher records.
     */
    public function getFilteredPaginated(string $search, int $limit, int $offset): array
    {
        $sql = "SELECT 
            teachers.id,
            teachers.phone,
            teachers.qualification,
            users.name,
            users.email,
            GROUP_CONCAT(courses.course_name SEPARATOR ', ') AS assigned_courses
        FROM teachers
        JOIN users ON teachers.user_id = users.id
        LEFT JOIN course_teacher ON teachers.id = course_teacher.teacher_id
        LEFT JOIN courses ON course_teacher.course_id = courses.id
        WHERE users.name LIKE :search 
           OR users.email LIKE :search 
           OR teachers.qualification LIKE :search
        GROUP BY teachers.id
        LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":search", '%' . $search . '%', \PDO::PARAM_STR);
        $stmt->bindValue(":limit", $limit, \PDO::PARAM_INT);
        $stmt->bindValue(":offset", $offset, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Find a teacher by their user ID.
     * 
     * @param int $userId The user ID associated with the teacher.
     * @return array|null Returns the teacher record as an associative array, or null if not found.
     */
    public function findByUserId(int $userId): ?array
    {
        $sql = "SELECT t.id, t.user_id, t.phone, t.qualification, t.created_at,
                   u.name, u.email, u.role
            FROM teachers t
            JOIN users u ON u.id = t.user_id
            WHERE t.user_id = :user_id
            LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Count the total number of filtered teacher records.
     * 
     * @param string $search Search term for filtering teachers.
     * @return int Returns the total count of filtered teacher records.
     */
    public function countFiltered(string $search): int
    {
        $sql = "
        SELECT COUNT(*) as total
        FROM teachers
        JOIN users ON teachers.user_id = users.id
        WHERE users.name LIKE :search 
           OR users.email LIKE :search 
           OR teachers.qualification LIKE :search
    ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":search", '%' . $search . '%', \PDO::PARAM_STR);
        $stmt->execute();

        return (int)$stmt->fetchColumn();
    }

    /**
     * Count the total number of teacher records.
     * 
     * @return int Returns the total count of teacher records.
     */
    public function countAll(): int
    {
        return (int)$this->db->query("SELECT COUNT(*) FROM teachers")->fetchColumn();
    }


    /**
     * Find a teacher by their ID.
     * 
     * @param int $id The ID of the teacher.
     * @return array|null Returns the teacher record as an associative array, or null if not found.
     */
    public function findById(int $id): ?array
    {
        $sql = "SELECT t.id, t.user_id, t.phone, t.qualification, t.created_at,
                   u.name, u.email, u.role
            FROM teachers t
            JOIN users u ON u.id = t.user_id
            WHERE t.id = :id
            LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Update a teacher record.
     * 
     * @param int $id The ID of the teacher to update.
     * @param array $data Associative array containing 'phone' and 'qualification'.
     * @return bool Returns true on success, false on failure.
     */
    public function update($id, array $data)
    {
        $sql = "UPDATE teachers SET phone = :phone, qualification = :qualification WHERE id = :id";
        $data['id'] = $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    /**
     * Get the courses assigned to a teacher.
     *
     * @param int $teacherId The ID of the teacher.
     * @return array Returns an array of assigned courses.
     */
    public function getAssignedCourses(int $teacherId): array
    {
        $sql = "
        SELECT 
            c.id, 
            c.course_name, 
            c.description, 
            c.created_at 
        FROM courses c
        JOIN course_teacher ct ON c.id = ct.course_id
        WHERE ct.teacher_id = :teacher_id
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':teacher_id' => $teacherId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
     * Assign multiple courses to a teacher.
     * 
     * @param int $teacherId The ID of the teacher.
     * @param array $courseIds Array of course IDs to assign.
     * @return bool Returns true on success, false on failure.
     */
    public function assignCourses(int $teacherId, array $courseIds): bool
    {
        $sql = "INSERT INTO course_teacher (teacher_id, course_id, assigned_at) VALUES (:teacher_id, :course_id, :assigned_at)";
        $stmt = $this->db->prepare($sql);

        // Begin transaction to ensure atomicity
        $this->db->beginTransaction();

        try {
            foreach ($courseIds as $courseId) {
                $stmt->execute([
                    ':teacher_id' => $teacherId,
                    ':course_id' => $courseId,
                    ':assigned_at' => date('Y-m-d H:i:s') // Current timestamp
                ]);
            }

            // Commit transaction
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            // Rollback transaction on failure
            $this->db->rollBack();
            return false;
        }
    }
}
