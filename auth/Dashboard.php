<?php
session_start();

// Inkluder sikkerhedsklassen
require_once __DIR__ . '/Security.php'; 

// Tjek om der er et token i cookies
if (isset($_COOKIE['auth_token'])) {
    $jwt = $_COOKIE['auth_token'];
    $userId = Security::validateJWT($jwt); // Valider token

    if (!$userId) {
        // Hvis tokenet ikke er validt, log brugeren ud
        setcookie('auth_token', '', time() - 3600, '/', '', true, true); // Slet cookie
        header("Location: /auth/login.php");
        exit();
    }

    // Hvis token er validt, vis dashboard
    echo "Velkommen, $userId! Du er logget ind.";
} else {
    header("Location: /auth/login.php");
    exit();
}
?>
