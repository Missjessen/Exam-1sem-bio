<?php
// Security.php
class Security {
    public static function startSession() {
        session_start();
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Strict'
        ]);
    }

    public static function sanitizeString($input) {
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }

    public static function validateInt($input) {
        return filter_var($input, FILTER_VALIDATE_INT);
    }

    public static function logout() {
        session_unset();
        session_destroy();
        header("Location: login.php");
        exit();
    }

    public static function checkLogin() {
        if (!isset($_SESSION['admin_id'])) {
            header("Location: login.php");
            exit();
        }
    }
}
?>
