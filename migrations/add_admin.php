<?php

require_once __DIR__ . '/../app/config/bootstrap.php';

use App\Config\Database;

try {
    $db = Database::getConnection();

    $stmt = $db->prepare("
        INSERT INTO users (name, email, password, role)
        VALUES (:name, :email, :password, :role)
    ");

    $stmt->execute([
        ':name'     => 'Super Admin',
        ':email'    => 'admin@example.com',
        ':password' => password_hash('Admin@123', PASSWORD_BCRYPT),
        ':role'     => 'admin'
    ]);

    echo "Admin user created successfully";
} catch (PDOException $e) {
    die("Error adding admin: " . $e->getMessage());
}
