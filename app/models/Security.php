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
     * Omdirigerer til login eller 403, hvis brugeren ikke opfylder kravene.
     *
     * @param bool $isAdmin Angiver, om kun admin-adgang er påkrævet.
     */
    public static function checkLogin($isAdmin = false) {
        // Tjek om brugeren er logget ind
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit();
        }

        // Tjek om admin-adgang er påkrævet
        if ($isAdmin && (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin')) {
            header('Location: /403');
            exit();
        }
    }

    /**
     * Hasher en adgangskode ved hjælp af PHP's native funktion.
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
     * @return bool Returnerer true, hvis adgangskoden matcher.
     */
    public static function validatePassword($password, $hash) {
        return password_verify($password, $hash);
    }
}
