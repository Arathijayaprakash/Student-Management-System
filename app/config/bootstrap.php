<?php

/**
 * Bootstrap File
 * 
 * This file is responsible for initializing the application by:
 * - Loading the Composer autoloader to include all dependencies.
 * - Loading environment variables from the `.env` file using the `vlucas/phpdotenv` library.
 * 
 * Environment variables are used to store sensitive configuration data such as database credentials,
 * API keys, and other environment-specific settings. These variables are loaded into PHP's `$_ENV`
 * and `$_SERVER` superglobals for use throughout the application.
 * 
 * @package StudentManagementSystem
 * @author  Arathi
 * @version 1.0
 */

use Dotenv\Dotenv;

// Include the Composer autoloader to load all dependencies
require_once __DIR__ . '/../../vendor/autoload.php';

/**
 * Load Environment Variables
 * 
 * The `Dotenv` class from the `vlucas/phpdotenv` library is used to load environment variables
 * from the `.env` file located in the root directory of the project.
 * 
 * - `createImmutable()`: Ensures that environment variables cannot be overridden once loaded.
 * - `load()`: Loads the variables into PHP's `$_ENV` and `$_SERVER` superglobals.
 */
$dotenv = Dotenv::createImmutable(__DIR__ . "/../../");
$dotenv->load();
