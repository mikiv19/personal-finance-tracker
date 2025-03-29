<?php
use PHPUnit\Framework\TestCase;
use App\Models\Transaction;
use App\Models\User;

class TransactionTest extends AuthenticationTest {
    private $transaction;
    private $user;

    protected function setUp(): void {
        parent::setUp();
        $this->transaction = new Transaction();
        $this->user = new User();
    }

    public function testCreateTransaction() {
        $userId = $this->user->register(uniqid().'@test.com', 'pass123');
        
        $txnId = $this->transaction->create([
            'user_id' => $userId,
            'amount' => 99.99,
            'category' => 'Food',
            'date' => '2024-05-01'
        ]);
        
        $this->assertIsInt($txnId);
    }

    public function testGetTransactions() {
        $userId = $this->user->register(uniqid().'@test.com', 'pass123');
        $this->transaction->create([
            'user_id' => $userId,
            'amount' => 50.00,
            'category' => 'Transport',
            'date' => '2024-05-02'
        ]);
        
        $transactions = $this->transaction->getAll($userId);
        $this->assertCount(1, $transactions);
        $this->assertEquals(50.00, $transactions[0]['amount']);
    }

    public function testInvalidCategory() {
        $userId = $this->user->register(uniqid().'@test.com', 'pass123');
        
        $this->expectException(\InvalidArgumentException::class);
        $this->transaction->create([
            'user_id' => $userId,
            'amount' => 100.00,
            'category' => 'InvalidCategory123',
            'date' => '2024-05-03'
        ]);
    }
}