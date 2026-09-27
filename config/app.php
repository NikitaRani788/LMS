<?php
/**
 * PRODUCTION CONFIGURATION
 * Load environment variables and set production settings
 */

// Load environment variables
if (file_exists(__DIR__ . '/.env')) {
    $env = parse_ini_file(__DIR__ . '/.env');
} else {
    $env = [];
}

// Application Environment
define('APP_ENV', $env['APP_ENV'] ?? 'production');
define('APP_DEBUG', filter_var($env['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN));
define('APP_NAME', $env['APP_NAME'] ?? 'Learning Management System');
define('APP_URL', $env['APP_URL'] ?? 'https://sql205.infinityfree.com');

// Security Settings
define('SESSION_LIFETIME', intval($env['SESSION_LIFETIME'] ?? 7200)); // 2 hours
define('SESSION_SECURE', filter_var($env['SESSION_SECURE'] ?? true, FILTER_VALIDATE_BOOLEAN));
define('SESSION_HTTPONLY', filter_var($env['SESSION_HTTPONLY'] ?? true, FILTER_VALIDATE_BOOLEAN));
define('SESSION_SAMESITE', $env['SESSION_SAMESITE'] ?? 'Strict');

// File Upload Settings
define('MAX_FILE_SIZE', intval($env['MAX_FILE_SIZE'] ?? 52428800)); // 50MB
define('ALLOWED_FILE_TYPES', explode(',', $env['ALLOWED_FILE_TYPES'] ?? 'pdf,doc,docx,xls,xlsx,ppt,pptx,txt,csv,jpg,jpeg,png,gif,zip,rar'));

// Error Reporting based on environment
if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
} else {
    error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    ini_set('log_errors', '1');
    ini_set('error_log', __DIR__ . '/../logs/php_errors.log');
}

// Session Configuration
ini_set('session.cookie_lifetime', SESSION_LIFETIME);
ini_set('session.gc_maxlifetime', SESSION_LIFETIME);
ini_set('session.cookie_secure', SESSION_SECURE ? '1' : '0');
ini_set('session.cookie_httponly', SESSION_HTTPONLY ? '1' : '0');
ini_set('session.cookie_samesite', SESSION_SAMESITE);

// Security Headers
header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

// HTTPS Enforcement (only in production)
if (APP_ENV === 'production' && (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on')) {
    if (!isset($_SERVER['HTTP_X_FORWARDED_PROTO']) || $_SERVER['HTTP_X_FORWARDED_PROTO'] !== 'https') {
        $host = $_SERVER['HTTP_HOST'] ?? 'sql205.infinityfree.com';
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        header('Location: https://' . $host . $uri);
        exit;
    }
}

// Timezone
date_default_timezone_set('UTC');

// Create logs directory if it doesn't exist
$logsDir = __DIR__ . '/../logs';
if (!is_dir($logsDir)) {
    mkdir($logsDir, 0755, true);
}

// Create uploads directory if it doesn't exist
$uploadsDir = __DIR__ . '/../uploads';
if (!is_dir($uploadsDir)) {
    mkdir($uploadsDir, 0755, true);
}

// Create user files directory
$userFilesDir = __DIR__ . '/../uploads/user_files';
if (!is_dir($userFilesDir)) {
    mkdir($userFilesDir, 0755, true);
}
?>