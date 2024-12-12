<?php 

class ContactController {
    private $contactModel;

    public function __construct($db) {
        $this->contactModel = new ContactModel($db);
    }

    public function handleFormSubmission() {
        try {
            // Tjek CSRF-token
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                throw new Exception("CSRF Validation Failed");
            }

            // Valider reCAPTCHA
            if (empty($_POST['g-recaptcha-response']) || !$this->contactModel->validateRecaptcha($_POST['g-recaptcha-response'])) {
                throw new Exception("ReCAPTCHA validation failed.");
            }

            // Sanitering af input
            $name = $this->contactModel->sanitizeInput($_POST['name']);
            $email = $this->contactModel->sanitizeInput($_POST['email']);
            $subject = $this->contactModel->sanitizeInput($_POST['subject']);
            $message = $this->contactModel->sanitizeInput($_POST['message']);

            // Validering af email
            if (!$this->contactModel->validateEmail($email)) {
                throw new Exception("Ugyldig email-adresse.");
            }

            // Sæt mailheaders og send e-mail
            $to = "Missjessen87@gmail.com"; // Modtagerens e-mail
            $headers = "From: noreply@cruise-nights-cinema.dk\r\n";
            $headers .= "Reply-To: $email\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
            $emailMessage = "Navn: $name\nEmail: $email\n\nBesked:\n$message";

            if ($this->contactModel->sendMail($to, $subject, $emailMessage, $headers)) {
                return "Din besked er sendt!";
            } else {
                throw new Exception("Der opstod en fejl ved afsendelse af beskeden.");
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
