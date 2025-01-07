<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
//ob_start();

define('BASE_URL', rtrim((isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']), '/') . '/');



function currentPageURL($page, $additionalParams = []) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? "https" : "http");
    $host = $_SERVER['HTTP_HOST'];

    // Start med en ny base-URL
    $queryParams = ['page' => $page];
    
    // Tilføj evt. ekstra parametre som slug
    foreach ($additionalParams as $key => $value) {
        $queryParams[$key] = $value;
    }

    // Generér URL
    $queryString = http_build_query($queryParams);
    return $protocol . '://' . $host . '/index.php?' . $queryString;
}

// Inkluder nødvendige filer
/* require_once 'core/autoLoader.php'; */
require_once __DIR__ . '/core/autoLoader.php';
try {
    $db = Database::getInstance()->getConnection();
    error_log("Databaseforbindelse er klar.");
} catch (Exception $e) {
    error_log("Fejl i databaseforbindelsen: " . $e->getMessage());
    die("Kunne ikke oprette databaseforbindelse.");
}
// Initialiser Database-forbindelsen via singleton
try {
    $query = $db->prepare("SELECT * FROM movies");
    $query->execute();
    $results = $query->fetchAll();
} catch (PDOException $e) {
    error_log("SQL-fejl: " . $e->getMessage());
    die("SQL-fejl.");
}