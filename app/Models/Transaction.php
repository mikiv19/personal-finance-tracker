<?php
namespace App\Models;

class Transaction {
    private $db;

    public function __construct() {
        global $db;
        $this->db = $db;
    }

    public function create(int $userId, array $data) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO transactions 
                (user_id, amount, description, type, category, transaction_date)
                VALUES (:user_id, :amount, :description, :type, :category, :transaction_date)
            ");

            $stmt->execute([
                'user_id' => $userId,
                'amount' => $data['amount'],
                'description' => $data['description'],
                'type' => $data['type'],
                'category' => $data['category'],
                'transaction_date' => $data['transaction_date']
            ]);

            return $this->db->lastInsertId();

        } catch (\PDOException $e) {
            error_log("Transaction Error: " . $e->getMessage());
            throw new \Exception("Failed to create transaction");
        }
    }

    public function getAllForUser(int $userId) {
        $stmt = $this->db->prepare("
            SELECT * FROM transactions 
            WHERE user_id = ?
            ORDER BY transaction_date DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}