<?php
// Database Connection Configuration
// Load environment variables if available
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);
if (file_exists(__DIR__ . '/../.env')) {
    $env = parse_ini_file(__DIR__ . '/../.env');
    define('DB_HOST', $env['DB_HOST'] ?? 'sql205.infinityfree.com');
    define('DB_USER', $env['DB_USER'] ?? 'if0_41817906');
    define('DB_PASS', $env['DB_PASS'] ?? 'RYhG8je5Pa');
    define('DB_NAME', $env['DB_NAME'] ?? 'if0_41817906_lms');
} else {
    // Fallback to constants (for development)
    define('DB_HOST', 'sql205.infinityfree.com');
    define('DB_USER', 'if0_41817906');
    define('DB_PASS', 'RYhG8je5Pa');
    define('DB_NAME', 'if0_41817906_lms');
}

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
        )
    );
} catch (PDOException $e) {
    // Production-safe error handling
    error_log('Database Connection Failed: ' . $e->getMessage());
    die('Database connection error. Please contact administrator.');
}
?>
