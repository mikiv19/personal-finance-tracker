<?php
namespace App\Models;

class Transaction {
    private $db;
    public function getAll(int $userId): array {
        $stmt = $this->db->prepare("
            SELECT * FROM transactions 
            WHERE user_id = ? 
            ORDER BY transaction_date DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}