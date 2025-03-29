<?php
namespace App\Controllers;

use App\Models\Transaction; 
use Exception; 

class TransactionController {
    public function getTransactions() {
        header('Content-Type: application/json');        
        try {
            session_start(); 
            if (!isset($_SESSION['user_id'])) {
                throw new Exception("Unauthorized", 401);
            }

            $userId = $_SESSION['user_id'];
            $transactions = (new Transaction())->getAll($userId);
            
            echo json_encode($transactions, JSON_THROW_ON_ERROR);
            
        } catch(Exception $e) {
            http_response_code($e->getCode() ?: 500);
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
    }
}