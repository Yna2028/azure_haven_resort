
<?php

// Start the session
session_start();

// Database configuration
$host = 'localhost';
$db   = 'azure_haven';
$user = 'root';
$pass = '';

// Connect to the MySQL database
try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);

    exit(
        'Database connection failed. '
        . 'Import database.sql in phpMyAdmin and check config.php.'
    );
}

// Escape output to help prevent XSS
function e($value)
{
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        'UTF-8'
    );
}

// Require an authenticated user
function require_user()
{
    if (empty($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
}

// Require an administrator
function require_admin()
{
    if (($_SESSION['role'] ?? '') !== 'admin') {
        header('Location: login.php');
        exit;
    }
}

?>
