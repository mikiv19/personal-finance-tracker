<?php

namespace App\Controllers; 
class TransactionController {


    public function create(array $data) {
        // Validate required fields
        $required = ['amount', 'description', 'type', 'date'];
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                throw new \InvalidArgumentException("Missing required field: $field");
            }
        }
    
        $stmt = $this->db->prepare("
            INSERT INTO transactions 
            (user_id, amount, description, type, category, date)
            VALUES (:user_id, :amount, :description, :type, :category, :date)
        ");
    
        $stmt->execute([
            'user_id' => $_SESSION['user_id'],
            'amount' => $data['amount'],
            'description' => $data['description'],
            'type' => $data['type'],
            'category' => $data['category'],
            'date' => $data['date']
        ]);
    
        return $this->db->lastInsertId();
    }
}