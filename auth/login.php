<?php
session_start();

// Inkluder sikkerhedsklassen
require_once '../models/Security.php';

// Simuleret brugerdata (skift til en database i produktion)
$validUser = [
    'username' => 'testuser',
    'password' => '$2y$10$9GV2CZQbe7GRp2mjGJ9p7OiGszfA9peYdP7wAx2zkTcfHOs64LSSm' // Hashet version af "testpassword"
];

// Tjek om brugeren har sendt en login-formular
if (isset($_POST['username']) && isset($_POST['password'])) {
    if ($_POST['username'] === $validUser['username'] && Security::validatePassword($_POST['password'], $validUser['password'])) {

        // Generér JWT-token
        $jwt = Security::generateJWT($validUser['username']);

        // Sæt JWT-token i en sikker cookie
        setcookie('auth_token', $jwt, time() + 3600, '/', '', true, true); // Cookie varer i 1 time
        setcookie('token_user', $validUser['username'], time() + 3600, '/', '', true, true);

        // Omvej til dashboard
        header("Location: /views/dashboard.php");
        exit();
    } else {
        echo "Ugyldigt brugernavn eller adgangskode!";
    }
} else {
    echo "Indtast venligst brugernavn og adgangskode.";
}
?>

<!-- HTML-formular til login -->
<form method="POST">
    <input type="text" name="username" placeholder="Brugernavn" required>
    <input type="password" name="password" placeholder="Adgangskode" required>
    <button type="submit">Log ind</button>
</form>
