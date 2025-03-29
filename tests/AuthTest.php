<?php
use PHPUnit\Framework\TestCase;
use App\Models\User;
use App\Controllers\AuthController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class AuthTest extends TestCase
{
    private $db;
    private $user;

    protected function setUp(): void
    {
        // Create test database connection
        $this->db = new PDO('sqlite::memory:');
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create tables
        $this->db->exec("
            CREATE TABLE users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                email VARCHAR(255) UNIQUE NOT NULL,
                password_hash VARCHAR(255) NOT NULL
            )
        ");

        // Initialize User model with test DB
        $this->user = new User();
        $reflection = new ReflectionClass($this->user);
        $property = $reflection->getProperty('db');
        $property->setAccessible(true);
        $property->setValue($this->user, $this->db);
    }

    protected function tearDown(): void
    {
        $this->db = null;
    }

    private function createRequest(array $params, string $method = 'POST'): Request
    {
        $request = Request::create('/', $method, $params);
        $session = new Session(new MockArraySessionStorage());
        $request->setSession($session);
        return $request;
    }

    public function testSuccessfulRegistration()
    {
        $request = $this->createRequest([
            'email' => 'test@example.com',
            'password' => 'SecurePass123!'
        ]);

        $controller = new AuthController();
        $controller->register($request);

        $user = $this->user->findByEmail('test@example.com');
        $this->assertIsArray($user);
        $this->assertEquals('test@example.com', $user['email']);
        $this->assertTrue(password_verify('SecurePass123!', $user['password_hash']));
    }

    public function testDuplicateRegistration()
    {
        // First registration
        $this->testSuccessfulRegistration();

        // Second attempt
        $request = $this->createRequest([
            'email' => 'test@example.com',
            'password' => 'AnotherPass456!'
        ]);

        $controller = new AuthController();
        $controller->register($request);

        $session = $request->getSession();
        $this->assertEquals('Email already exists', $session->get('error'));
    }

    public function testInvalidRegistrationData()
    {
        $testCases = [
            [
                'input' => ['email' => '', 'password' => 'pass'],
                'expected' => 'All fields are required'
            ],
            [
                'input' => ['email' => 'invalid-email', 'password' => 'pass'],
                'expected' => 'Invalid email format'
            ],
            [
                'input' => ['email' => 'test@example.com', 'password' => 'short'],
                'expected' => 'Password must be at least 8 characters'
            ]
        ];

        foreach ($testCases as $case) {
            $request = $this->createRequest($case['input']);
            $controller = new AuthController();
            $controller->register($request);
            
            $session = $request->getSession();
            $this->assertEquals($case['expected'], $session->get('error'));
        }
    }

    public function testSuccessfulLogin()
    {
        // Register first
        $this->testSuccessfulRegistration();

        $request = $this->createRequest([
            'email' => 'test@example.com',
            'password' => 'SecurePass123!'
        ]);

        $controller = new AuthController();
        $response = $controller->login($request);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/dashboard', $response->headers->get('Location'));
        $this->assertEquals(1, $request->getSession()->get('user_id'));
    }

    public function testInvalidLoginCredentials()
    {
        $testCases = [
            [
                'input' => ['email' => 'wrong@example.com', 'password' => 'pass'],
                'expected' => 'Invalid email or password'
            ],
            [
                'input' => ['email' => 'test@example.com', 'password' => 'wrongpass'],
                'expected' => 'Invalid email or password'
            ]
        ];

        foreach ($testCases as $case) {
            $request = $this->createRequest($case['input']);
            $controller = new AuthController();
            $controller->login($request);
            
            $session = $request->getSession();
            $this->assertEquals($case['expected'], $session->get('error'));
        }
    }

    public function testAccessProtectedRouteWithoutAuth()
    {
        $request = $this->createRequest([], 'GET');
        $response = (new DashboardController())->index($request);
        
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/login', $response->headers->get('Location'));
    }

    public function testLogout()
    {
        $request = $this->createRequest([]);
        $request->getSession()->set('user_id', 1);
        
        $controller = new AuthController();
        $response = $controller->logout($request);
        
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/login', $response->headers->get('Location'));
        $this->assertNull($request->getSession()->get('user_id'));
    }

    public function testFindByEmail()
    {
        $this->db->exec("
            INSERT INTO users (email, password_hash)
            VALUES ('test@example.com', 'hashed_password')
        ");

        $user = $this->user->findByEmail('test@example.com');
        $this->assertIsArray($user);
        $this->assertEquals('test@example.com', $user['email']);
    }

    public function testCreateUser()
    {
        $this->user->create([
            'email' => 'new@example.com',
            'password_hash' => password_hash('password', PASSWORD_DEFAULT)
        ]);

        $stmt = $this->db->query("SELECT * FROM users WHERE email = 'new@example.com'");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $this->assertIsArray($result);
        $this->assertEquals('new@example.com', $result['email']);
    }
}