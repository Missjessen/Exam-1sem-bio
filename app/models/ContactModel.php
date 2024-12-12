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

    public function sendMail($to, $subject, $message, $headers) {
        return mail($to, $subject, $message, $headers);
    }
}
