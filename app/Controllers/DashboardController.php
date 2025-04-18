<?php
namespace App\Controllers; 

use App\Models\SummaryCalculator;
use App\Models\Transaction;
use Exception;

class DashboardController {

public function getSummary() {
    try {
        header('Content-Type: application/json');

        if (!isset($_SESSION['id'])) {
            throw new Exception("Unauthorized", 401);
        }

        $userId = $_SESSION['id'];
        $calculator = new SummaryCalculator();
        $summary = $calculator->getMonthlySummary($userId);
        
        
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