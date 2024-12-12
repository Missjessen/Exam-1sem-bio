<?php
require_once __DIR__ . '/init.php';

// Feedback variabel
$response = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Hent og valider input fra formularen
    $name = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : '';
    $email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '';
    $subject = isset($_POST['subject']) ? htmlspecialchars(trim($_POST['subject'])) : '';
    $message = isset($_POST['message']) ? htmlspecialchars(trim($_POST['message'])) : '';

    // Validering af input
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response = "Ugyldig email-adresse.";
    } elseif (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $response = "Alle felter skal udfyldes.";
    } else {
        // Modtagerens email (din virksomheds e-mail)
        $to = "nsj@cruise-nights-cinema.dk";

        // Email-indhold
        $body = "Du har modtaget en ny besked fra kontaktformularen:\n\n";
        $body .= "Navn: $name\n";
        $body .= "Email: $email\n";
        $body .= "Emne: $subject\n\n";
        $body .= "Besked:\n$message\n";

        // Headers
        $headers = "From: nsj@cruise-nights-cinema.dk\r\n"; // Fast afsender fra dit domæne
        $headers .= "Reply-To: $email\r\n"; // Kundens email
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        // Send mail
        if (mail($to, $subject, $body, $headers)) {
            $response = "Tak for din besked, $name! Vi vender tilbage hurtigst muligt.";
        } else {
            $response = "Der opstod en fejl ved afsendelse af din besked.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontakt os</title>
    <style>
        .contact-form { max-width: 500px; margin: 0 auto; }
        .form-group { margin-bottom: 15px; }
        .contact-message { color: green; }
        .error-message { color: red; }
    </style>
</head>
<body>
    <div class="contact-form">
        <h3>Kontakt Os</h3>
        <?php if (!empty($response)): ?>
            <p class="<?= strpos($response, 'Tak') !== false ? 'contact-message' : 'error-message' ?>">
                <?= htmlspecialchars($response) ?>
            </p>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="name">Navn:</label>
                <input type="text" id="name" name="name" required placeholder="Dit navn">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required placeholder="Din emailadresse">
            </div>
            <div class="form-group">
                <label for="subject">Emne:</label>
                <input type="text" id="subject" name="subject" required placeholder="Emnet for din besked">
            </div>
            <div class="form-group">
                <label for="message">Besked:</label>
                <textarea id="message" name="message" rows="4" required placeholder="Skriv din besked her..."></textarea>
            </div>
            <button type="submit" name="submit">Send besked</button>
        </form>
    </div>
</body>
</html>
