<?php
require_once __DIR__ . '/init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    // Validering
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $contactMessage = "Alle felter skal udfyldes!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $contactMessage = "Ugyldig emailadresse.";
    } else {
        // Send email (eller gem i database)
        if (mail("kontakt@dinwebside.dk", $subject, $message, "From: $email")) {
            $contactMessage = "Din besked er sendt!";
        } else {
            $contactMessage = "Der opstod en fejl. Prøv igen.";
        }
    }
}
?>
