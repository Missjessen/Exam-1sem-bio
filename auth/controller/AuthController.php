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
            $_SESSION['role'] = 'user';
        } else {
            throw new Exception("Forkert email eller adgangskode.");
        }
    }

    public function loginAdmin($email, $password) {
        session_start();
        $admin = $this->adminModel->getAdminByEmail($email);

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['role'] = 'admin';
        } else {
            throw new Exception("Forkert email eller adgangskode.");
        }
    }

    public function registerUser($name, $email, $password) {
        if ($this->userModel->emailExists($email)) {
            throw new Exception("Emailen er allerede registreret.");
        }
        $this->userModel->createUser($name, $email, $password);
    }
}
