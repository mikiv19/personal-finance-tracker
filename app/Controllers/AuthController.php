<?php
namespace App\Controllers; 
use App\Models\User;

class AuthController {
    public function showLogin() {
    }

    public function showRegister() {
    }

    public function register() {
        try {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                throw new \Exception('All fields are required');
            }

            $user = new User();
            if ($user->findByEmail($email)) {
                throw new \Exception('Email already exists');
            }

            $user->create([
                'email' => $email,
                'password_hash' => password_hash($password, PASSWORD_DEFAULT)
            ]);
    
            session_write_close();
            header('Location: /login');
            exit;
    
        } catch (\Exception $e) {
            error_log("Registration error: " . $e->getMessage());
            $_SESSION['error'] = $e->getMessage();
            header('Location: /register');
            exit;
        }
    }

    public function login() {
        
        try {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $user = (new User())->findByEmail($email);
            
            if (!$user) {
                throw new \Exception('Email not found');
            }
            
            if (!password_verify($password, $user['password_hash'])) {
                throw new \Exception('Invalid password');
            }
    
            $_SESSION['user_id'] = $user['id'];
            session_write_close();
            header('Location: /dashboard');
            exit;
            
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Invalid email or password';
            session_write_close();
            header('Location: /login');
            exit;
        }
    }

    public function logout() {
        session_destroy();
        header('Location: /login');
        exit;
    }
}