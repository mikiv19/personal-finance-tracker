<?php
namespace App\Controllers; 
class TransactionController {
    
public function getTransactions() {
    header('Content-Type: application/json');
    session_start();
    
    try {
        if (!isset($_SESSION['user_id'])) {
            throw new Exception("Unauthorized", 401);
        }

        $userId = $_SESSION['user_id'];
        $transactions = (new Transaction())->getAll($userId);
        
        echo json_encode($transactions);
        
    } catch(Exception $e) {
        http_response_code($e->getCode() ?: 500);
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}
}