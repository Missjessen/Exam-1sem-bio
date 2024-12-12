<?php
require_once __DIR__ . '/init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Modtagerens email (udskift med din egen emailadresse)
    $mymail = "nsj@cruise-nights-cinema.dk";

    // Hent data fra POST
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $subject = isset($_POST['subject']) ? $_POST['subject'] : '';
    $message = isset($_POST['message']) ? $_POST['message'] : '';

    // Regex til validering af email
    $regexp = "/^[^0-9][A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/";

    // Validering
    if (!preg_match($regexp, $email)) {
        $response = "Ugyldig email-adresse.";
    } elseif (empty($email) || empty($message) || empty($subject)) {
        $response = "Alle felter skal udfyldes.";
    } else {
        // Email-indhold
        $body = "Besked:\n$message\n\nAfsenderens email: $email";

        // Headers
        $headers = "From: noreply@yourdomain.com\r\n"; // Fast afsender fra dit domæne
        $headers .= "Reply-To: $email\r\n"; // Brugerens email i Reply-To
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        // Send mail
        if (mail($mymail, $subject, $body, $headers)) {
            $response = "Mail blev sendt til $mymail!";
        } else {
            $response = "Mail blev ikke sendt. Der opstod en fejl.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Mail</title>
</head>
<body>
    <h1>Mail Test Script</h1>
    <?php if (!empty($response)): ?>
        <p><?= htmlspecialchars($response) ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="email">Din Email:</label>
        <input type="email" id="email" name="email" required placeholder="Indtast din email">

        <label for="subject">Emne:</label>
        <input type="text" id="subject" name="subject" required placeholder="Indtast emne">

        <label for="message">Besked:</label>
        <textarea id="message" name="message" rows="4" required placeholder="Skriv din besked"></textarea>

        <button type="submit" name="submit">Send Test Mail</button>
    </form>
</body>
</html>

