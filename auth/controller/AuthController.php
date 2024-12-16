<?php 

class AuthController {
    private $userModel;
    private $adminModel;
   

    public function __construct($db) {
        $this->userModel = new UserModel($db);
        $this->adminModel = new AdminModel($db);
       
    }

    public function loginUser($email, $password) {
        session_start();
        $user = $this->userModel->getUserByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['name'];
            return true;
        }
        throw new Exception("Forkert email eller adgangskode.");
    }

     // Admin login-metode
     public function loginAdmin($email, $password) {
        session_start();

        $admin = $this->adminModel->getAdminByEmail($email);
        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['username'] = $admin['name'];
            $_SESSION['role'] = 'admin';
            return true;
        }
        throw new Exception("Forkert email eller adgangskode.");
    }


    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        header("Location: " . BASE_URL . "index.php?page=homePage");
        exit;
    }

    public function registerUser($name, $email, $password) {
        if ($this->userModel->emailExists($email)) {
            throw new Exception("Emailen er allerede registreret.");
        }

        // Opret brugeren
        $this->userModel->createUser($name, $email, $password);

        // Sæt en besked og omdiriger til login-siden
        $_SESSION['message'] = "Din profil er oprettet. Du kan nu logge ind.";
        header("Location: index.php?page=login");
        exit;
    }

}
