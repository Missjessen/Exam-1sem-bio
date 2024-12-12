<?php
require_once __DIR__ . '/init.php'; // Inkluder basisopsætning

$response = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Hent og valider input fra formularen
    $name = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : '';
    $email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '';
    $subject = isset($_POST['subject']) ? htmlspecialchars(trim($_POST['subject'])) : '';
    $message = isset($_POST['message']) ? htmlspecialchars(trim($_POST['message'])) : '';

    // Validering
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response = "Ugyldig email-adresse.";
    } elseif (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $response = "Alle felter skal udfyldes.";
    } else {
        $to = "nsj@cruise-nights-cinema.dk"; // Din virksomheds e-mail
        $body = "Du har modtaget en ny besked fra kontaktformularen:\n\n";
        $body .= "Navn: $name\n";
        $body .= "Email: $email\n";
        $body .= "Emne: $subject\n\n";
        $body .= "Besked:\n$message\n";

        $headers = "From: nsj@cruise-nights-cinema.dk\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        // Send email
        if (mail($to, $subject, $body, $headers)) {
            $response = "Tak for din besked, $name! Vi vender tilbage hurtigst muligt.";
        } else {
            $response = "Der opstod en fejl ved afsendelse af din besked.";
        }
    }

    // Gem responsen i sessionen
    session_start();
    $_SESSION['contactMessage'] = $response;

    // Omdiriger tilbage til forsiden eller viewet
    header("Location: " . BASE_URL . "index.php?page=homePage#contact");
    exit;
} else {
    // Direkte adgang omdirigeres
    header("Location: " . BASE_URL . "index.php?page=homePage");
    exit;
}
