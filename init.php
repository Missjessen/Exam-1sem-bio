<?php
ob_start(); // Start output buffering for at undgå header-fejl

// Aktivér fejlrapportering
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Definér den aktuelle side og slug
define('BASE_URL', '/');




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


