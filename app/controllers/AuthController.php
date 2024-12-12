<?php 

class AuthController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function login() {
        session_start(); // Start session

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            // Find bruger via email
            $stmt = $this->db->prepare("SELECT * FROM customers WHERE email = :email");
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Tjek adgangskode og sæt session
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['message'] = "Du er nu logget ind!";

                // Omdiriger til forrige side eller forsiden
                $redirect = $_SESSION['redirect_after_login'] ?? BASE_URL . 'index.php?page=homePage';
                unset($_SESSION['redirect_after_login']); // Ryd redirect-session
                header("Location: " . $redirect);
                exit;
            } else {
                return "Forkert email eller adgangskode.";
            }
        }
    }

    public function register() {
        session_start(); // Start session

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash adgangskode

            // Tjek om email allerede findes
            $stmt = $this->db->prepare("SELECT * FROM customers WHERE email = :email");
            $stmt->execute([':email' => $email]);
            if ($stmt->fetch()) {
                return "Emailen er allerede registreret.";
            }

            // Indsæt bruger i databasen
            $stmt = $this->db->prepare("INSERT INTO customers (username, email, password) VALUES (:username, :email, :password)");
            $stmt->execute([':username' => $username, ':email' => $email, ':password' => $hashedPassword]);

            $_SESSION['message'] = "Din profil er oprettet. Log venligst ind.";
            header("Location: " . BASE_URL . "index.php?page=login");
            exit;
        }
    }
}
