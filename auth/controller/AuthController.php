<?php 

class AuthController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function loginUser($email, $password) {
        try {
            $query = "SELECT id, name, password FROM customers WHERE email = :email";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
        
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
            if ($user && password_verify($password, $user['password'])) {
                // Debugging
                error_log("Bruger fundet og verificeret: " . print_r($user, true));
                
                // Sæt brugeroplysninger i sessionen
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                return true;
            } else {
                error_log("Forkert adgangskode eller bruger ikke fundet: $email");
            }
        
            return false;
        } catch (PDOException $e) {
            throw new Exception("Fejl ved login: " . $e->getMessage());
        }
    }
    
    
    
    

    public function registerUser($name, $email, $password) {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
            $query = "
                INSERT INTO customers (name, email, password)
                VALUES (:name, :email, :password)
            ";
    
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
    
            if ($stmt->execute()) {
                // Sæt brugeroplysninger i session
                $_SESSION['user_id'] = $this->db->lastInsertId();
                $_SESSION['user_name'] = $name; // Gem brugerens navn i sessionen
                return true;
            }
    
            return false;
        } catch (PDOException $e) {
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
        // Start session, hvis nødvendigt
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    
        // Fjern alle session-data
        session_unset();
        session_destroy();
    
        // Slet session-cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        }
    
        // Omdiriger brugeren
        if (!headers_sent()) {
            header("Location: index.php?page=homePage");
            exit();
        } else {
            echo "<script>window.location.href='index.php?page=homePage';</script>";
            exit();
        }
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
