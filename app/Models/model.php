<?php

namespace App\Models;

use App\Config\Database;
use PDO;

/**
 * Base Model Class
 * 
 * Provides a foundation for all models in the application by establishing a database connection.
 */
class Model
{
    /**
     * @var PDO $db Database connection instance.
     */
    protected PDO $db;

    /**
     * Constructor
     * Initializes the database connection using the Database configuration.
     */
    public function __construct()
    {
        $this->db = Database::getConnection();
    }
}
