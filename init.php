<?php
require_once 'core/autoLoader.php';



// Start session, hvis ikke allerede startet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Midlertidig databaseforbindelse
try {
    $host = 'cjsfkt3sf.mysql.service.one.com'; // Midlertidig DB_HOST (opdater hvis nødvendigt)
    $dbName = 'cjsfkt3sf_cruisenightscinema'; // Midlertidig DB_NAME
    $user = 'root'; // Midlertidig DB_USER
    $password = '123456'; // Midlertidig DB_PASS
    $charset = 'utf8mb4'; // Midlertidig DB_CHARSET

    $dsn = "mysql:host=$host;dbname=$dbName;charset=$charset";
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Debugging: Bekræft, at forbindelsen er oprettet
    error_log("Midlertidig databaseforbindelse oprettet!");
} catch (PDOException $e) {
    // Log fejlen og afslut
    error_log("Midlertidig databaseforbindelse fejlede: " . $e->getMessage());
    die("Midlertidig databaseforbindelse fejlede. Kontakt administratoren.");
}

// Global variabel til at bruge forbindelsen i resten af projektet
$GLOBALS['pdo'] = $pdo;


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
