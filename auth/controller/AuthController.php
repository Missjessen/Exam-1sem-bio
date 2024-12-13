<?php 

class AuthController {
    private $userModel;
    private $adminModel;

    public function __construct($db) {
        $this->userModel = new UserModel($db);
        $this->adminModel = new AdminModel($db);
    }

    public function loginUser($email, $password) {
        $user = $this->userModel->getUserByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['name'];
            $_SESSION['role'] = 'user';
            return true;
        }
        throw new Exception("Forkert email eller adgangskode.");
    }

    public function loginAdmin($email, $password) {
        $admin = $this->adminModel->getAdminByEmail($email);

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['name'];
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
}
