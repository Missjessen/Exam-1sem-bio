<?php 
class AuthController {
    private $db;

    public function __construct($db) {
        $this->db = $db;

        // Start session, hvis det ikke allerede er gjort
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function loginUser($email, $password) {
        try {
            // Login-logik her (ingen ændringer nødvendige)
            $query = "SELECT id, name, password FROM customers WHERE email = :email";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                // Sæt brugeroplysninger i session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['last_activity'] = time(); // Til inaktivitetshåndtering
                return true;
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
    
    public function autoLogout() {
        // Tidsgrænse for inaktivitet (30 minutter)
        $inactiveLimit = 1800; // 30 minutter i sekunder

        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $inactiveLimit) {
            // Hvis brugeren har været inaktiv for længe, log ud
            $this->logoutUser();
            header("Location: index.php?page=login&message=timeout"); // Eventuel omdirigering med besked
            exit();
        }

        // Opdater sidste aktivitetstidspunkt
        $_SESSION['last_activity'] = time();
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

        // Slet session-cookies
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
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
    



 public function loginAdmin($email, $password) {
    try {
        // Log bruger ud, hvis bruger-session eksisterer
        if (isset($_SESSION['user_id'])) {
            session_unset();
            session_destroy();
        }

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

