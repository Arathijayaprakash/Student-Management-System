<?php

namespace App\Models;

use App\Config\Database;
use PDO;
use PDOException;

class User
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function findByUsername(string $username): ?array
    {
        $sql = "SELECT * FROM users WHERE email = :username LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

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
}
