<?php
namespace App\Models;

class User {
    private $db;

    public function __construct() {
        global $db;
        $this->db = $db;
    }

    public function create(array $data) {
        $stmt = $this->db->prepare("
            INSERT INTO users (email, password_hash) 
            VALUES (:email, :password_hash)
        ");
        $stmt->execute($data);
    }
    
    public function findByEmail(string $email): ?array {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }
}