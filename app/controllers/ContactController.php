<?php 

class ContactController {
    private $contactModel;

    public function __construct($db) {
        $this->contactModel = new ContactModel($db);
    }

    public function handleFormSubmission() {
        try {
            // Tjek om formularen er indsendt
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception("Ugyldig forespørgsel.");
            }
    
            // Tjek om CSRF-token matcher
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                throw new Exception("CSRF Validation Failed.");
            }
    
            // Saniter og valider input
            $name = htmlspecialchars(trim($_POST['name']));
            $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
            $subject = htmlspecialchars(trim($_POST['subject']));
            $message = htmlspecialchars(trim($_POST['message']));
    
            if (empty($name) || empty($email) || empty($subject) || empty($message)) {
                throw new Exception("Alle felter skal udfyldes.");
            }
    
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Ugyldig email-adresse.");
            }
    
            // Send email
            $to = "your_email@example.com"; // Din modtager-email
            $headers = "From: noreply@yourdomain.com\r\n";
            $headers .= "Reply-To: $email\r\n";
            $body = "Navn: $name\nEmail: $email\n\nBesked:\n$message";
    
            if (!mail($to, $subject, $body, $headers)) {
                throw new Exception("Der opstod en fejl ved afsendelse af beskeden.");
            }
    
            // Forny CSRF-token efter vellykket indsendelse
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    
            return "Din besked er sendt!";
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    
}
