<?php 
class AuthController {
    private $customerModel;

    public function __construct($db) {
        $this->customerModel = new CustomerModel($db);
    }

    // Handle user login
    public function login($email, $password) {
        session_start();

        $user = $this->customerModel->getCustomerByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['name'];
            $_SESSION['message'] = "Welcome back, {$user['name']}!";
            return true;
        }
        throw new Exception("Invalid email or password.");
    }

    // Handle user registration
    public function register($name, $email, $password) {
        session_start();

        // Check if email already exists
        if ($this->customerModel->emailExists($email)) {
            throw new Exception("Email is already registered.");
        }

        // Create the user
        $this->customerModel->createUser($name, $email, $password);
        $_SESSION['message'] = "Your account has been created successfully. Please log in.";
    }

    // Logout user
    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        header("Location: " . BASE_URL . "index.php?page=homePage");
        exit;
    }
}
