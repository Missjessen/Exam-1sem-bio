<?php
// Start output buffering for sikkerhed
ob_start();
require_once __DIR__ . '/init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Indsamler data fra POST
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Validerer data
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $contactMessage = "Alle felter skal udfyldes!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $contactMessage = "Ugyldig emailadresse.";
    } else {
        // Forsøger at sende email
        $recipient = "nsj@cruise-nights-cinema.dk "; // Skift til din email
        $headers = "From: $email";

        if (mail($recipient, $subject, $message, $headers)) {
            $contactMessage = "Din besked er sendt!";
        } else {
            $contactMessage = "Der opstod en fejl. Prøv igen.";
        }
    }

    // Gem besked i session for at vise den på forsiden
    session_start();
    $_SESSION['contactMessage'] = $contactMessage;

    // Omdiriger til forsiden
    header("Location: " . BASE_URL . "index.php?page=homePage");
    exit;
} else {
    // Hvis nogen prøver at tilgå direkte
    header("Location: " . BASE_URL . "index.php?page=homePage");
    exit;
}
