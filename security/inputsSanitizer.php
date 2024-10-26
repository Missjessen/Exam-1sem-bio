<?php
// /app/models/AdminSettings.php
require_once '../core/BaseModel.php';
class Utility {
    public static function sanitizeInput($input) {
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }

    public static function redirectTo($url) {
        header("Location: $url");
        exit();
    }
}
?>