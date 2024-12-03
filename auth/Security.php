<?php
class Security {
    public static function startSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
            session_set_cookie_params([
                'lifetime' => 0,
                'path' => '/',
                'secure' => isset($_SERVER['HTTPS']),
                'httponly' => true,
                'samesite' => 'Strict'
            ]);
        }
    }

    public static function checkLogin($isAdmin = false) {
        self::startSession();

         // Tillad adgang uden login for bestemte IP-adresser
         $allowedIPs = ['127.0.0.1', '::1']; // Lokale IP'er
         if (in_array($_SERVER['REMOTE_ADDR'], $allowedIPs)) {
             error_log("Login springes over for IP: " . $_SERVER['REMOTE_ADDR']);
             return;
         }

        // Tillad adgang til statiske filer som all.js
        if (strpos($_SERVER['REQUEST_URI'], 'all.js') !== false) {
            return;
        }

        if ($isAdmin && !isset($_SESSION['admin_logged_in'])) {
            header("Location: /Exam-1sem-bio/auth/login.php");
            exit();
        }

        if (!$isAdmin && !isset($_SESSION['user_logged_in'])) {
            header("Location: /Exam-1sem-bio/auth/login.php");
            exit();
        }

        // Session timeout (30 minutter)
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > 1800) {
            self::logout();
        }

        $_SESSION['last_activity'] = time();
    }


    public static function validateRecaptcha($recaptchaResponse) {
        $secretKey = "DIN_SECRET_KEY";
        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$recaptchaResponse");
        $responseKeys = json_decode($response, true);

        return isset($responseKeys["success"]) && $responseKeys["success"] === true;
    }
}
