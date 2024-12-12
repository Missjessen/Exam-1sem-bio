<?php 
<?php

class ContactController {
    public function handleContactForm($name, $email, $subject, $message) {
        // Modtagerens email
        $to = "nsj@cruise-nights-cinema.dk";

        // Email-indhold
        $body = "Du har modtaget en ny besked fra kontaktformularen:\n\n";
        $body .= "Navn: $name\n";
        $body .= "Email: $email\n";
        $body .= "Emne: $subject\n\n";
        $body .= "Besked:\n$message\n";

        // Headers
        $headers = "From: nsj@cruise-nights-cinema.dk\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        // Send mail
        if (mail($to, $subject, $body, $headers)) {
            return "Tak for din besked, $name! Vi vender tilbage hurtigst muligt.";
        } else {
            return "Der opstod en fejl ved afsendelse af din besked.";
        }
    }
}

    
