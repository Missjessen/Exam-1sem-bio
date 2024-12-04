<?php
// Lokal sti
define('LOCAL_BASE_PATH', $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio');

// Online sti
define('SERVER_BASE_PATH', '/customers/8/1/a/cjsfkt3sf/webroots/a050556f/Exam-1sem-bio');

// Brug dynamisk BASE_PATH som standard
if (strpos($_SERVER['DOCUMENT_ROOT'], 'xamppfiles') !== false) {
    define('BASE_PATH', LOCAL_BASE_PATH);
} else {
    define('BASE_PATH', SERVER_BASE_PATH);
}

// Debugging
error_log("Lokal sti: " . LOCAL_BASE_PATH);
error_log("Server sti: " . SERVER_BASE_PATH);
error_log("Aktiv BASE_PATH: " . BASE_PATH);

// Sikring mod direkte adgang
//if (realpath($_SERVER['SCRIPT_FILENAME']) === realpath(__FILE__)) {
    //die('Direct access is not allowed.');
//}

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
