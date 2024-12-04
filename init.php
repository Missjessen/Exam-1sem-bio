<?php
// Definer projektets rodmappe som en konstant
define('BASE_PATH', $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/');

// Sikring mod direkte adgang
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    die('Direct access is not allowed.');
}

// Start session, hvis ikke allerede startet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inkluder autoloader
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/core/autoLoader.php';

// Debugging: Bekræft BASE_PATH
error_log("BASE_PATH er: " . BASE_PATH);




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
