<?php
namespace App\Controllers; 

use App\Models\SummaryCalculator;
use App\Models\Transaction;
use Exception;

class DashboardController {
    private $userId;

    public function __construct() {
        $this->userId = $_SESSION['user_id'] ?? null;
        
        if (!$this->userId) {
            header('Location: /login');
            exit;
        }
    }

    public function getTransactions() {
        header('Content-Type: application/json');
        try {
            $transactions = (new Transaction())->getAll($userId);
            echo json_encode($transactions);
        } catch(Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
    }

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
