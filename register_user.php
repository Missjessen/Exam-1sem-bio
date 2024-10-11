<?php
require_once 'includes/connection.php';
require_once 'oop/Security.php';

Security::startSession();

// Tjek, om administratoren er logget ind
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Resten af registreringsformularen
?>

<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <title>Opret Bruger</title>
</head>
<body>
    <h2>Opret en ny bruger</h2>
    <form method="POST" action="register_process.php">
        <label for="username">Brugernavn:</label>
        <input type="text" name="username" required>

        <label for="email">Email:</label>
        <input type="email" name="email" required>

        <label for="password">Adgangskode:</label>
        <input type="password" name="password" required>

        <label for="confirm_password">Bekræft Adgangskode:</label>
        <input type="password" name="confirm_password" required>

        <button type="submit">Opret Bruger</button>
    </form>
</body>
</html>
