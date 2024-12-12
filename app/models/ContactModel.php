<?php 

class ContactModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function sanitizeInput($input) {
        return htmlspecialchars(trim($input));
    }

    public function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public function validateRecaptcha($recaptchaResponse) {
        $secretKey = 'your-secret-key'; // Din reCAPTCHA-secret key
        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$recaptchaResponse");
        $responseData = json_decode($response);
        return $responseData->success;
    }

    public function sendMail($to, $subject, $message, $headers) {
        return mail($to, $subject, $message, $headers);
    }
}
