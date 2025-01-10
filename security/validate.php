<?php 
class Validate {
    // Validerer en e-mailadresse
    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    // Tjekker, om alle nødvendige felter er udfyldt
    public static function validateRequiredFields($fields) {
        foreach ($fields as $field) {
            if (empty($field)) {
                return false; // Returnér false, hvis et felt er tomt
            }
        }
        return true; // Alle felter er udfyldt
    }
}
