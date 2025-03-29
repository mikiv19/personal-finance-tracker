<?php
class UserTest extends PHPUnit\Framework\TestCase {
    public function testUserCreation() {
        $user = new App\Models\User();
        $user->create('test@example.com', 'password123');
        $this->assertNotEmpty($user->getId());
    }
}