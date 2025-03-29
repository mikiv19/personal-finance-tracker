<?php
namespace App\Models;

class SummaryCalculator {
    private $db;

    public function __construct() {
        global $db;
        $this->db = $db;
    }

    public function getMonthlySummary(int $userId): array {
        // Income
        $income = $this->db->prepare("
            SELECT SUM(amount) AS total 
            FROM transactions 
            WHERE user_id = ? AND amount > 0
        ");
        $income->execute([$userId]);
        
        // Expenses
        $expenses = $this->db->prepare("
            SELECT SUM(amount) AS total 
            FROM transactions 
            WHERE user_id = ? AND amount < 0
        ");
        $expenses->execute([$userId]);

        // Categories
        $categories = $this->db->prepare("
            SELECT category, SUM(amount) AS total 
            FROM transactions 
            WHERE user_id = ? AND amount < 0 
            GROUP BY category
        ");
        $categories->execute([$userId]);

        return [
            'total_income' => (float) ($income->fetchColumn() ?? 0),
            'total_expenses' => (float) (abs($expenses->fetchColumn()) ?? 0),
            'by_category' => $categories->fetchAll(\PDO::FETCH_KEY_PAIR)
        ];
    }
}