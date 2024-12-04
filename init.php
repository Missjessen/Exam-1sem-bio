<?php
define('ROOT', __DIR__ . '');
require_once 'core/autoLoader.php';



// Start session, hvis ikke allerede startet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Initialiser Database-forbindelsen via singleton
try {
    $db = Database::getInstance()->getConnection(); // Hent singleton-forbindelse
    error_log("Databaseforbindelse oprettet!");
} catch (Exception $e) {
    error_log("Fejl i databaseforbindelsen: " . $e->getMessage());
    die("Databasefejl.");
}


// Singleton-test (valgfrit)
try {
    $db1 = Database::getInstance()->getConnection();
    $db2 = Database::getInstance()->getConnection();
    if ($db1 === $db2) {
        error_log("Singleton virker: begge forbindelser er identiske.");
    } else {
        error_log("Fejl: forbindelserne er ikke ens.");
    }
} catch (Exception $e) {
    error_log("Fejl under singleton-test: " . $e->getMessage());
}
