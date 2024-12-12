<?php 


class ContactController {

        public function handleContactForm($formData) {
            try {
                // Valider data
                $name = trim($formData['name']);
                $email = trim($formData['email']);
                $subject = trim($formData['subject']);
                $message = trim($formData['message']);
    
                if (empty($name) || empty($email) || empty($subject) || empty($message)) {
                    return "Alle felter skal udfyldes!";
                }
    
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    return "Ugyldig emailadresse.";
                }
    
                // Send besked (tilpas mail-logikken efter behov)
                $recipient = "kontakt@dinwebside.dk"; // Skift til din emailadresse
                $headers = "From: $email";
    
                if (mail($recipient, $subject, $message, $headers)) {
                    return "Din besked er sendt!";
                } else {
                    return "Der opstod en fejl. Prøv igen.";
                }
            } catch (Exception $e) {
                error_log("Fejl i kontaktformular: " . $e->getMessage());
                return "Der opstod en fejl. Kontakt support.";
            }
        }
    }
    


    
