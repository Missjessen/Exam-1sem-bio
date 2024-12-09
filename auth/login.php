<?php
require_once __DIR__ . '/Security.php';
Security::startSession();

// Dummy-brugerdata
$validUser = [
    'username' => 'admin',
    'password' => Security::hashPassword('password'), // Hashed adgangskode
    'role' => 'admin'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === $validUser['username'] && Security::validatePassword($password, $validUser['password'])) {
        // Indstil session
        $_SESSION['user_id'] = 1; // Dummy ID
        $_SESSION['user_role'] = $validUser['role'];

        // Omdiriger til dashboard
        header('Location: /index.php?page=admin_dashboard');
        exit();
    } else {
        echo "Ugyldigt brugernavn eller adgangskode.";
    }
}
?>

<!-- Simpel login-formular -->
<form method="POST">
    <input type="text" name="username" placeholder="Brugernavn" required>
    <input type="password" name="password" placeholder="Adgangskode" required>
    <button type="submit">Log ind</button>
</form>
