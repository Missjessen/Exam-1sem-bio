<?php

class Security {

    /**
     * Starter en session, hvis den ikke allerede er startet.
     */
    public static function startSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Tjekker, om brugeren er logget ind, og om det er en admin, hvis påkrævet.
     *
     * @param bool $isAdmin Hvis true, tjekker for admin-adgang.
     */
   /*  public static function checkLogin($isAdmin = false) {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login.php');
            exit();
        }

        if ($isAdmin && (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin')) {
            header('Location: /403.php');
            exit();
        }
    } */

    /**
     * Hasher en adgangskode.
     *
     * @param string $password Adgangskoden, der skal hashes.
     * @return string Det hashed password.
     */
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * Validerer en adgangskode mod en hash.
     *
     * @param string $password Adgangskoden indtastet af brugeren.
     * @param string $hash Det hashed password fra databasen.
     * @return bool True, hvis adgangskoden matcher.
     */
    public static function validatePassword($password, $hash) {
        return password_verify($password, $hash);
    }
}
