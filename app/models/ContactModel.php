<?php
class ContactModel {
    private $db;
    private $secretKey;

    public function __construct($db) {
        $this->db = $db;
        $this->secretKey = getenv('RECAPTCHA_SECRET_KEY'); // Fra .env
    }

    // Validerer reCAPTCHA
    public function validateRecaptcha($recaptchaResponse) {
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = [
            'secret' => $this->secretKey,
            'response' => $recaptchaResponse
        ];

        // Send HTTP POST-anmodning
        $options = [
            'http' => [
                'method'  => 'POST',
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'content' => http_build_query($data),
            ]
        ];
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $responseKeys = json_decode($result, true);
        return intval($responseKeys['success']) === 1;
    }

    // Saniterer og validerer inputdata
    public function sanitizeInput($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }

    public function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public function sendMail($to, $subject, $message, $headers) {
        // Mail funktion (kan tilpasses efter behov)
        return mail($to, $subject, $message, $headers);
    }
}
