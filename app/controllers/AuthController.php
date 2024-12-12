<?php

class AuthController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function login() {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            $stmt = $this->db->prepare("SELECT * FROM customers WHERE email = :email");
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                // Omdiriger til ønsket side efter login
                $redirect = $_SESSION['redirect_after_login'] ?? BASE_URL . 'index.php';
                unset($_SESSION['redirect_after_login']);
                header("Location: " . $redirect);
                exit;
            } else {
                return "Forkert email eller adgangskode.";
            }
        }
    }
}
