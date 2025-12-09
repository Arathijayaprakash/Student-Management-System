<?php

namespace App\Config;

use PDO;
use PDOException;

/**
 * Database Connection Class
 * 
 * This class implements the Singleton Pattern to manage a single instance of the database connection
 * using PHP's PDO (PHP Data Objects). It ensures efficient use of resources by reusing the same
 * connection throughout the application.
 * 
 * Environment variables for the database connection are loaded from the `.env` file.
 * 
 * @package StudentManagementSystem
 * @author  Arathi
 * @version 1.0
 */
class Database
{
    /**
     * @var PDO|null Holds the single instance of the PDO connection.
     */
    private static ?PDO $instance = null;

    /**
     * Private constructor to prevent direct instantiation.
     */
    private function __construct() {}
    /**
     * Private clone method to prevent cloning of the instance.
     */
    private function __clone() {}

    /**
     * Get the PDO database connection instance.
     * 
     * This method checks if the connection instance is already created. If not, it initializes
     * the connection using the environment variables and returns the instance.
     * 
     * @return PDO The PDO database connection instance.
     * @throws PDOException If the connection fails.
     */
    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            try {
                // Fetch database configuration from environment variables
                $host = $_ENV['DB_HOST'];
                $db = $_ENV['DB_NAME'];
                $user = $_ENV['DB_USER'];
                $pass = $_ENV['DB_PASS'];
                $port = $_ENV['DB_PORT'];

                // Data Source Name (DSN)
                $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";

                // Create a new PDO instance
                self::$instance = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_PERSISTENT => true
                ]);
            } catch (PDOException $e) {
                // Handle connection errors
                die("Database connection failed: " . $e->getMessage());
            }
        }

        return self::$instance;
    }
}
