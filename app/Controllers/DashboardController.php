<?php
namespace App\Controllers; 

use App\Models\SummaryCalculator;
use App\Models\Transaction;
use Exception;

class DashboardController {

public function getSummary() {
    try {
        header('Content-Type: application/json');
        session_start();

        if (!isset($_SESSION['user_id'])) {
            throw new Exception("Unauthorized", 401);
        }

        $userId = $_SESSION['user_id'];
        $calculator = new SummaryCalculator();
        $summary = $calculator->getMonthlySummary($userId);
        
        echo json_encode($summary, JSON_THROW_ON_ERROR);
        
    } catch(Throwable $e) {
        error_log("Summary Error: " . $e->getMessage()); // Log to PHP error log
        http_response_code($e->getCode() ?: 500);
        echo json_encode([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
    exit;
}
}