<?php
require_once '../init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = $_POST['role'] ?? 'user';

    try {
        $authController = new AuthController($db);

        if ($role === 'admin') {
            $authController->loginAdmin($email, $password);
            header("Location: " . BASE_URL . "index.php?page=admin_dashboard");
        } else {
            $authController->loginUser($email, $password);
            header("Location: " . BASE_URL . "index.php?page=profile");
        }
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

include '../view/login.php';
