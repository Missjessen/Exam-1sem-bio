<?php 

class ContactController {
    public function handleContactForm() {
        $feedback = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
            // Hent og rens data
            $name = htmlspecialchars(trim($_POST['name']));
            $email = htmlspecialchars(trim($_POST['email']));
            $subject = htmlspecialchars(trim($_POST['subject']));
            $message = htmlspecialchars(trim($_POST['message']));

            // Validering
            if (empty($name) || empty($email) || empty($subject) || empty($message)) {
                $feedback = "Alle felter skal udfyldes.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $feedback = "Ugyldig email-adresse.";
            } else {
                // Opsætning af email
                $to = "nsj@cruise-nights-cinema.dk"; // Din modtager-email
                $headers = "From: nsj@cruise-nights-cinema.dk\r\n";
                $headers .= "Reply-To: $email\r\n";
                $body = "Navn: $name\nEmail: $email\n\nBesked:\n$message";

                // Send mail
                if (mail($to, $subject, $body, $headers)) {
                    $feedback = "Tak for din besked, $name! Vi vender tilbage hurtigst muligt.";
                } else {
                    $feedback = "Der opstod en fejl ved afsendelse af din besked. Prøv igen senere.";
                }
            }
        }

        return $feedback; // Returnér feedback til PageController
    }
}
