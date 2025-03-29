<?php
namespace App\Models;

class User {
    private $db;

    public function __construct() {
        global $db;
        $this->db = $db;
    }

    public function register(string $email, string $password): int {
        // Validate email/password format first (add tests later)
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Invalid email format");
        }

        $stmt = $this->db->prepare("
            INSERT INTO users (email, password_hash)
            VALUES (?, ?)
        ");
        $stmt->execute([
            $email,
            password_hash($password, PASSWORD_DEFAULT)
        ]);
        return $this->db->lastInsertId();
    }

    public function login(string $email, string $password): bool {
        $stmt = $this->db->prepare("
            SELECT password_hash FROM users WHERE email = ?
        ");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        return $user && password_verify($password, $user['password_hash']);
    }
}