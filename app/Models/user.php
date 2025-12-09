<?php

namespace App\Models;

use PDO;

/**
 * User Model
 * 
 * Handles database operations related to users, including creation, retrieval, updating, and deletion.
 */
class User extends Model
{
    /**
     * Find a user by their username (email).
     * 
     * @param string $username The email of the user.
     * @return array|null Returns the user record as an associative array, or null if not found.
     */
    public function findByUsername(string $username): ?array
    {
        $sql = "SELECT * FROM users WHERE email = :username LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    /**
     * Find a user by their ID.
     * 
     * @param int $id The ID of the user.
     * @return array|null Returns the user record as an associative array, or null if not found.
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Create a new user record.
     * 
     * @param array $data Associative array containing 'name', 'email', 'password', and 'role'.
     * @return int Returns the ID of the newly created user.
     */
    public function create(array $data)
    {
        $sql = "INSERT INTO users (name, email, password, role) 
                VALUES (:name, :email, :password, :role)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ":name"     => $data["name"],
            ":email"    => $data["email"],
            ":password" => $data["password"],
            ":role"     => $data["role"]
        ]);

        return $this->db->lastInsertId(); // return user_id
    }

    /**
     * Update a user record.
     * 
     * @param int $id The ID of the user to update.
     * @param array $data Associative array containing 'name' and 'email'.
     * @return bool Returns true on success, false on failure.
     */
    public function update($id, array $data)
    {
        $sql = "UPDATE users SET name = :name, email = :email WHERE id = :id";
        $data['id'] = $id;

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    /**
     * Delete a user record.
     * 
     * @param int $id The ID of the user to delete.
     * @return bool Returns true on success, false on failure.
     */
    public function delete($id)
    {
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    /**
     * Update the password of a user.
     * 
     * @param int $userId The ID of the user whose password is being updated.
     * @param string $hashedPassword The new hashed password.
     * @return bool Returns true on success, false on failure.
     */
    public function updatePassword(int $userId, string $hashedPassword): bool
    {
        $sql = "UPDATE users SET password = :password WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':password' => $hashedPassword,
            ':id' => $userId
        ]);
    }
}
