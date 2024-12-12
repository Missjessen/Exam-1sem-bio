<?php
require_once __DIR__ . '/init.php';

$response = ''; // Variabel til at vise feedback til brugeren

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Hent og valider input fra formularen
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Validering af email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response = "Ugyldig email-adresse.";
    } elseif (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $response = "Alle felter skal udfyldes.";
    } else {
        // Modtagerens email
        $to = "nsj@cruise-nights-cinema.dk"; // Skift til din egen emailadresse
        $body = "Navn: $name\nEmail: $email\n\nBesked:\n$message";

        // Headers
        $headers = "From: nsj@cruise-nights-cinema.dk\r\n"; // En gyldig afsender fra dit domæne
        $headers .= "Reply-To: $email\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        // Send mail
        if (mail($to, $subject, $body, $headers)) {
            $response = "Tak for din besked! Vi vender tilbage hurtigst muligt.";
        } else {
            $response = "Der opstod en fejl ved afsendelse af din besked.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontaktformular</title>
</head>
<body>
    <h1>Kontakt os</h1>
    <?php if ($response): ?>
        <p><?= htmlspecialchars($response) ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="name">Navn:</label>
        <input type="text" id="name" name="name" required placeholder="Dit navn">

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required placeholder="Din emailadresse">

        <label for="subject">Emne:</label>
        <input type="text" id="subject" name="subject" required placeholder="Emnet for din besked">

        <label for="message">Besked:</label>
        <textarea id="message" name="message" rows="4" required placeholder="Skriv din besked her..."></textarea>

        <button type="submit" name="submit">Send besked</button>
    </form>
</body>
</html>
