<?php
class ContactController {
    private $contactModel;

    public function __construct($db) {
        $this->contactModel = new ContactModel($db);
    }

    public function handleFormSubmission() {
        // Tjek CSRF-token
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
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
        $to = "Missjessen87@gmail.com";  // Modtager
        $headers = "From: $email";
        $emailMessage = "Navn: $name\nEmail: $email\nBesked: $message";
        if ($this->contactModel->sendMail($to, $subject, $emailMessage, $headers)) {
            return "Din besked er sendt!";
        } else {
            throw new Exception("Der opstod en fejl ved afsendelse af beskeden.");
        }
    }
}
