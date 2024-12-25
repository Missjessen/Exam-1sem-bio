<?php
//ob_start(); // Start output buffering for at undgå header-fejl
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Definér den aktuelle side og slug
define('BASE_URL', rtrim((isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']), '/') . '/');



function currentPageURL($page, $additionalParams = []) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? "https" : "http");
    $host = $_SERVER['HTTP_HOST'];
    $uri = strtok($_SERVER['REQUEST_URI'], '?'); // Fjern eksisterende query-parametre

    // Opdater query-parametre
    $queryParams = array_merge(['page' => $page], $additionalParams);

    // Generér ny URL
    return $protocol . "://" . $host . $uri . '?' . http_build_query($queryParams);
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