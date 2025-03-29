<?php
namespace App\Models;

class SummaryCalculator {
    private $db;

    public function __construct() {
        global $db;
        $this->db = $db;
    }

    public function getMonthlySummary(int $userId): array {

        $incomeStmt = $this->db->prepare("
            SELECT COALESCE(SUM(amount), 0) 
            FROM transactions 
            WHERE user_id = ? AND amount > 0
        ");
        $incomeStmt->execute([$userId]);


        $expenseStmt = $this->db->prepare("
            SELECT COALESCE(SUM(ABS(amount)), 0) 
            FROM transactions 
            WHERE user_id = ? AND amount < 0
        ");
        $expenseStmt->execute([$userId]);   


        $categoryStmt = $this->db->prepare("
            SELECT category, SUM(ABS(amount)) 
            FROM transactions 
            WHERE user_id = ? AND amount < 0 
            GROUP BY category
        ");
        $categoryStmt->execute([$userId]);  

        return [
            'total_income' => (float) $incomeStmt->fetchColumn(),
            'total_expenses' => (float) $expenseStmt->fetchColumn(),
            'by_category' => $categoryStmt->fetchAll(\PDO::FETCH_KEY_PAIR)
        ];
    }
}