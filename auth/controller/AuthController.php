<?php 

class AuthController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function loginUser($email, $password) {
        try {
            $query = "SELECT id, password FROM customers WHERE email = :email";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
    
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($user && password_verify($password, $user['password'])) {
                // Gem brugerdata i session
                $_SESSION['user_id'] = $user['id'];
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            throw new Exception("Fejl ved login: " . $e->getMessage());
        }
    }
    
    

    public function registerUser($name, $email, $password) {
        try {
            // Tjek for eksisterende bruger
            $query = "SELECT id FROM customers WHERE email = :email";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
    
            if ($stmt->fetch(PDO::FETCH_ASSOC)) {
                throw new Exception("En bruger med denne e-mail eksisterer allerede.");
            }
    
            // Hash adgangskode og indsæt ny bruger
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO customers (name, email, password) VALUES (:name, :email, :password)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
            $stmt->execute();
    
            return true;
        } catch (Exception $e) {
            throw new Exception("Fejl ved registrering: " . $e->getMessage());
        }
    }
    
    

    public function emailExists($email) {
        $query = "SELECT COUNT(*) FROM customers WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
    

    public function logoutUser() {
        // Slet session og log brugeren ud
        session_unset();
        session_destroy();
    }

    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public function getCurrentUserId() {
        return $_SESSION['user_id'] ?? null;
    }

    public function getUserById($userId) {
        $query = "SELECT id, name, email, created_at FROM customers WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
}
