<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', '/var/log/php_errors.log');

$host = $_ENV['DB_HOST'] ?? 'db';
$port = $_ENV['DB_PORT'] ?? '5432'; 
$dbname = $_ENV['DB_NAME'] ?? 'finance_db';
$user = $_ENV['DB_USER'] ?? 'finance_user';
$password = $_ENV['DB_PASSWORD'] ?? 'postgres';

try {
    $db = new PDO(
        "pgsql:host=$host;port=$port;dbname=$dbname",
        $user,
        $password
    );
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}