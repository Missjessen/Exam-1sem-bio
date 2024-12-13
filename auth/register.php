<?php
require_once '../init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    try {
        $authController = new AuthController($db);
        $authController->registerUser($name, $email, $password);

        header("Location: " . BASE_URL . "auth/login.php");
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

include '../view/register.php';
