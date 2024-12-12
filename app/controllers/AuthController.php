<?php 
class AuthController {
    private $db;
    private $customerModel;

    public function __construct($db) {
        $this->db = $db;
        $this->customerModel = $customerModel;
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
                $_SESSION['message'] = "Velkommen, {$user['username']}!";

                // Omdiriger til forrige side eller til forsiden
                $redirect = $_SESSION['redirect_after_login'] ?? BASE_URL . 'index.php?page=homePage';
                unset($_SESSION['redirect_after_login']); // Ryd redirect-session
                header("Location: " . $redirect);
                exit;
            } else {
                return "Forkert email eller adgangskode.";
            }
        }
    }


    public function register($name, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        return $this->customerModel->createCustomer($name, $email, $hashedPassword);
    }

    public function login($email, $password) {
        $user = $this->customerModel->getCustomerByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['name'];
            return true;
        }
        return false;
    }
    public function register() {
        session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Tjek om email allerede findes
            $stmt = $this->db->prepare("SELECT * FROM customers WHERE email = :email");
            $stmt->execute([':email' => $email]);
            if ($stmt->fetch()) {
                return "Emailen er allerede registreret.";
            }

            // Indsæt bruger i databasen
            $stmt = $this->db->prepare("INSERT INTO customers (username, email, password) VALUES (:username, :email, :password)");
            $stmt->execute([':username' => $username, ':email' => $email, ':password' => $hashedPassword]);

            $_SESSION['user_id'] = $this->db->lastInsertId();
            $_SESSION['username'] = $username;
            $_SESSION['message'] = "Din profil er oprettet. Velkommen, $username!";

            // Omdiriger til profilside
            header("Location: " . BASE_URL . "index.php?page=profilePage");
            exit;
        }
    }
}
