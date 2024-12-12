<?php 
class ContactController {
public function handleContactForm() {
    $response = ''; // Feedback-variabel

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
        // Hent og rens input
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
            // Modtagerens email
            $to = "nsj@cruise-nights-cinema.dk";

            // Email-indhold
            $body = "Du har modtaget en ny besked fra kontaktformularen:\n\n";
            $body .= "Navn: $name\n";
            $body .= "Email: $email\n";
            $body .= "Emne: $subject\n\n";
            $body .= "Besked:\n$message\n";

            // Headers
            $headers = "From: nsj@cruise-nights-cinema.dk\r\n"; // Fast afsender
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

    return $response;
}
}