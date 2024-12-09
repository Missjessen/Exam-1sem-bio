<?php

class Security {

        public static function startSession() {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
    }

    public static function checkLogin($isAdmin = false) {
        $debugMode = getenv('DEBUG_MODE') === 'true';

        if ($debugMode) {
            return;  // Deaktiver sikkerhedskontrol under udvikling
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit();
        }

        if ($isAdmin && $_SESSION['user_role'] !== 'admin') {
            header('Location: /403');
            exit();
        }
    }

    // Skift denne til en stærkere hemmelighed i produktion
    private static $secretKey = '(DinSuperSecretKey)';
    private static $algorithm = 'HS256'; // HMAC SHA256

    // Funktion til at generere JWT-token
    public static function generateJWT($userId) {
        $issuedAt = time();
        $expirationTime = $issuedAt + 3600; // Tokenet udløber om 1 time
        $payload = array(
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'sub' => $userId // Identifikation af brugeren
        );

        // Header for JWT
        $header = json_encode(array('typ' => 'JWT', 'alg' => self::$algorithm));

        // Base64 URL encode header og payload
        $base64UrlHeader = self::base64UrlEncode($header);
        $base64UrlPayload = self::base64UrlEncode(json_encode($payload));

        // Generer signatur med HMAC SHA256
        $signature = self::base64UrlEncode(hash_hmac('sha256', $base64UrlHeader . '.' . $base64UrlPayload, self::$secretKey, true));

        // Returner den færdige JWT
        return $base64UrlHeader . '.' . $base64UrlPayload . '.' . $signature;
    }

    // Funktion til at validere JWT-token
    public static function validateJWT($jwt) {
        $jwtParts = explode('.', $jwt);

        if (count($jwtParts) !== 3) {
            return false; // Hvis JWT ikke er i det korrekte format
        }

        list($base64UrlHeader, $base64UrlPayload, $signature) = $jwtParts;

        // Dekod JWT payload (for at få brugerID og udløbstid)
        $payload = json_decode(self::base64UrlDecode($base64UrlPayload), true);

        // Tjek om token er udløbet
        if ($payload['exp'] < time()) {
            return false; // Tokenet er udløbet
        }

        // Verificér signaturen
        $expectedSignature = self::base64UrlEncode(hash_hmac('sha256', $base64UrlHeader . '.' . $base64UrlPayload, self::$secretKey, true));

        if ($signature !== $expectedSignature) {
            return false; // Hvis signaturen ikke stemmer, er tokenet ugyldigt
        }

        return $payload['sub']; // Returner bruger-ID, hvis alt er validt
    }

    // Helper funktion til Base64 URL Encoding
    private static function base64UrlEncode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    // Helper funktion til Base64 URL Decoding
    private static function base64UrlDecode($data) {
        return base64_decode(strtr($data, '-_', '+/'));
    }

    // Funktion til at hashe adgangskoder med PHP's native hash funktion
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    // Funktion til at validere adgangskode mod hash
    public static function validatePassword($password, $hash) {
        return password_verify($password, $hash);
    }

  

}
?>
