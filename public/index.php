<?php
require __DIR__ . '/../app/config.php';
require __DIR__ . '/../vendor/autoload.php';
session_start();

$_SESSION['id'] = 1; // Simulate a logged-in user for testing


error_log("Request Path: " . $_SERVER['REQUEST_URI']);
$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
error_log("Parsed Path: " . $requestPath);

set_error_handler(function($severity, $message, $file, $line) {
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode([
        'error' => 'Server Error',
        'details' => "$message in $file:$line"
    ]);
    exit;
});



$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($requestPath) {
    case '/dashboard':
        require __DIR__ . '/views/dashboard.php';
        break;
    case '/api/summary':
        header('Content-Type: application/json');
        (new App\Controllers\DashboardController())->getSummary();
        break;
    case '/api/transactions':
        header('Content-Type: application/json');
        (new App\Controllers\DashboardController())->getTransactions();
        break;
    default:
        header('Content-Type: application/json');
        http_response_code(404);
        echo json_encode(['error' => 'Not Found']);
        exit;
}