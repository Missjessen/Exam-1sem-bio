<?php
session_start();

// Dummy database simulation
class TestDatabase {
    private $users = [];

    public function registerUser($name, $email, $password) {
        foreach ($this->users as $user) {
            if ($user['email'] === $email) {
                return false; // Email already exists
            }
        }
        $this->users[] = [
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ];
        return true;
    }

    public function loginUser($email, $password) {
        foreach ($this->users as $user) {
            if ($user['email'] === $email && password_verify($password, $user['password'])) {
                return $user; // Login successful
            }
        }
        return null; // Login failed
    }
}

// Dummy PageLoader
class TestPageLoader {
    public function renderPage($viewName, $data = [], $type = 'auth') {
        echo "Rendering view: $viewName<br>";
        if (!empty($data)) {
            echo "Data passed to view:<br>";
            print_r($data);
        }
    }

    public function renderErrorPage($code, $message) {
        echo "Error $code: $message<br>";
    }
}

// Auth Controller for testing
class TestAuthController {
    private $db;
    private $pageLoader;

    public function __construct($db) {
        $this->db = $db;
        $this->pageLoader = new TestPageLoader();
    }

    public function registerUser($name, $email, $password) {
        if (empty($name) || empty($email) || empty($password)) {
            $this->pageLoader->renderPage('register', ['error' => 'Alle felter skal udfyldes.']);
            return;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->pageLoader->renderPage('register', ['error' => 'Ugyldig email-adresse.']);
            return;
        }
        $registered = $this->db->registerUser($name, $email, $password);
        if ($registered) {
            $_SESSION['user_id'] = $email; // Simulate user ID
            echo "User successfully registered and logged in.<br>";
        } else {
            $this->pageLoader->renderPage('register', ['error' => 'Email allerede i brug.']);
        }
    }

    public function loginUser($email, $password) {
        if (empty($email) || empty($password)) {
            $this->pageLoader->renderPage('login', ['error' => 'Alle felter skal udfyldes.']);
            return;
        }
        $user = $this->db->loginUser($email, $password);
        if ($user) {
            $_SESSION['user_id'] = $email; // Simulate user ID
            echo "User successfully logged in.<br>";
        } else {
            $this->pageLoader->renderPage('login', ['error' => 'Forkert email eller adgangskode.']);
        }
    }
}

// Test scenarios
$testDb = new TestDatabase();
$authController = new TestAuthController($testDb);

// Test: Registrer ny bruger
echo "<h2>Test: Registrer ny bruger</h2>";
$authController->registerUser("John Doe", "john@example.com", "password123");

// Test: Forsøg registrering med samme email
echo "<h2>Test: Registrer med samme email</h2>";
$authController->registerUser("Jane Doe", "john@example.com", "password456");

// Test: Login med korrekt information
echo "<h2>Test: Login med korrekt information</h2>";
$authController->loginUser("john@example.com", "password123");

// Test: Login med forkert adgangskode
echo "<h2>Test: Login med forkert adgangskode</h2>";
$authController->loginUser("john@example.com", "wrongpassword");

// Test: Login med email, der ikke eksisterer
echo "<h2>Test: Login med ikke-eksisterende email</h2>";
$authController->loginUser("jane@example.com", "password456");
