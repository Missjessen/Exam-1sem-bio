<?php
require_once 'Security.php';
Security::startSession();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = Security::sanitizeString($_POST['username']);
    $password = Security::sanitizeString($_POST['password']);
    $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

    // Valider reCAPTCHA
    if (!Security::validateRecaptcha($recaptchaResponse)) {
        die("reCAPTCHA validering mislykkedes. Prøv igen.");
    }

    // Forbind til databasen
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        if ($user['role'] === 'admin') {
            $_SESSION['admin_logged_in'] = true;
        } else {
            $_SESSION['user_logged_in'] = true;
        }

        $_SESSION['last_activity'] = time(); // Start session timeout timer
        header("Location: /Exam-1sem-bio/homePage");
        exit();
    } else {
        echo "Forkert brugernavn eller adgangskode.";
    }
}
?>

<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <form method="POST" action="">
        <input type="text" name="username" placeholder="Brugernavn" required>
        <input type="password" name="password" placeholder="Adgangskode" required>
        <div class="g-recaptcha" data-sitekey="DIN_SITE_KEY"></div>
        <button type="submit">Login</button>
    </form>
</body>
</html>
