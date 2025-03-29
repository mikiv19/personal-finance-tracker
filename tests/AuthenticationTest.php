<?php
use PHPUnit\Framework\TestCase;
use App\Models\User;

class AuthenticationTest extends TestCase {
    private $user;

    protected function setUp(): void {
        $this->user = new User();
    }

    public function testUserRegistration() {
        $email = uniqid('test_').'@example.com'; // Unique email per test
        $password = "SecurePass123!";
        
        $userId = $this->user->register($email, $password);
        $this->assertIsInt($userId);
        
        // Test duplicate using NEW email
        $this->expectException(PDOException::class);
        $this->user->register($email, "DifferentPass456!");
    }

    public function testValidLogin() {
        $this->user->register("valid@example.com", "Pass123");
        $result = $this->user->login("valid@example.com", "Pass123");
        $this->assertTrue($result);
    }

    public function testInvalidLogin() {
        $this->user->register("invalid@example.com", "Pass123");
        $result = $this->user->login("invalid@example.com", "WrongPass");
        $this->assertFalse($result);
    }
}