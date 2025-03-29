<?php
require __DIR__ . '/../app/config.php';
require __DIR__ . '/../vendor/autoload.php';

use App\Controllers\AuthController;
use App\Controllers\DashboardController;

session_start();


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
    case '/login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new AuthController())->login();
        } else {
            (new AuthController())->showLogin();
            require __DIR__ . '/views/login.php';
        }
        break;

    case '/register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new AuthController())->register();
        } else {
            (new AuthController())->showRegister();
            require __DIR__ . '/views/register.php';
        }
        break;

    case '/logout':
        (new AuthController())->logout();
        break;

    case '/dashboard':
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
        exit;
        }
    require __DIR__ . '/views/dashboard.php';
    break;

    case '/api/summary':
        header('Content-Type: application/json');
        (new DashboardController())->getSummary();
        break;
    case '/api/transactions':
        header('Content-Type: application/json');
        (new DashboardController())->getTransactions();
        break;
    default:
        header('Content-Type: application/json');
        http_response_code(404);
        echo json_encode(['error' => 'Not Found']);
        exit;
}