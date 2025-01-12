<?php 

class AuthController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function loginUser($email, $password) {
        try {
            if (empty($email) || empty($password)) {
                throw new Exception("Email og adgangskode skal udfyldes.");
            }
    
            $query = "SELECT id, name, password FROM customers WHERE email = :email";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
    
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($user && password_verify($password, $user['password'])) {
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
            if (empty($name) || empty($email) || empty($password)) {
                throw new Exception("Alle felter skal udfyldes.");
            }
    
            // Tjek om email allerede findes
            if ($this->emailExists($email)) {
                throw new Exception("Denne email er allerede registreret.");
            }
    
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
                $_SESSION['user_name'] = $name;
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
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        }
        error_log("Brugeren er logget ud.");
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
    



 // Admin-login-funktion
 public function loginAdmin($email, $password) {
    try {
        $query = "SELECT id, name, password FROM employees WHERE email = :email AND role = 'admin'";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin['password'])) {
            // Sæt admin-session
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['name'];
            return true;
        }

        return false;
    } catch (PDOException $e) {
        throw new Exception("Fejl ved admin-login: " . $e->getMessage());
    }
}


// Admin-logout-funktion
public function logoutAdmin() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    unset($_SESSION['admin_id'], $_SESSION['admin_name']);
    session_destroy();
    header("Location: " . BASE_URL . "index.php?page=admin_login");
    exit();
}

// Tjek om admin er logget ind
public function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']);
}
}

