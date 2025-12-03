<?php

require_once __DIR__ . '/../app/config/bootstrap.php';

use App\Config\Database;

try {
    $db = Database::getConnection();

    $query = "
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(120) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            role ENUM('admin', 'teacher', 'student') DEFAULT 'student',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
    ";

    $db->exec($query);

    echo "Users table created successfully";
} catch (PDOException $e) {
    die("Migration failed: " . $e->getMessage());
}
