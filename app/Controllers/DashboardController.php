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
        
        if (!$this->userId) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }

        try {
            $transactions = (new Transaction())->getAllForUser($this->userId);
            echo json_encode($transactions);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
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

    public function addTransaction() {
        header('Content-Type: application/json');
        
        try {
            if (!$this->userId) {
                throw new \Exception("Not authenticated");
            }

            // Get and validate input
            $input = json_decode(file_get_contents('php://input'), true);
            
            $required = ['amount', 'description', 'type', 'category', 'transaction_date'];
            foreach ($required as $field) {
                if (empty($input[$field])) {
                    throw new \Exception("Missing field: $field");
                }
            }


            $transactionId = (new Transaction())->create($this->userId, $input);
            
            echo json_encode([
                'success' => true,
                'id' => $transactionId
            ]);

        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode([
                'error' => $e->getMessage()
            ]);
        }
    }
}