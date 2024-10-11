<?php
require_once 'includes/connection.php';
require_once 'oop/Security.php';

Security::startSession();

// Tjek, om administratoren er logget ind
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = Security::sanitizeString($_POST['username']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        echo "Adgangskoderne matcher ikke.";
        exit();
    }

    if (!$email) {
        echo "Indtast en gyldig emailadresse.";
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        $sql = "INSERT INTO users (username, email, password, is_validated) VALUES (:username, :email, :password, 0)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->execute();
        echo "Bruger oprettet succesfuldt! Afventer validering.";
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            echo "Brugernavn eller email er allerede i brug.";
        } else {
            echo "Der opstod en fejl: " . $e->getMessage();
        }
    }
}
?>
