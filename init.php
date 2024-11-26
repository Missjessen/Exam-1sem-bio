<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/core/autoloader.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/core/baseModel.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/core/baseController.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/app/services/PageLoader.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/core/Database.php'; 
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/config/env_loader.php';


$db = Database::getInstance()->getConnection();
if ($db instanceof PDO) {
    echo "Databaseforbindelse er gyldig.";
} else {
    echo "Databaseforbindelse fejler.";
}

try {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->query("SELECT * FROM movies LIMIT 1");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    print_r($result); // Vis det første resultat
} catch (PDOException $e) {
    echo "Databaseforespørgsel fejlede: " . $e->getMessage();
}

// Test Singleton funktionalitet
$db1 = Database::getInstance()->getConnection();
$db2 = Database::getInstance()->getConnection();

if ($db1 === $db2) {
    echo "Singleton virker: begge forbindelser er identiske.";
} else {
    echo "Fejl: forbindelserne er ikke ens.";
}

// Indlæs miljøvariabler fra .envq
try {
    loadEnv(__DIR__ . '/.env');
} catch (Exception $e) {
    die($e->getMessage());
}

// Start session
session_start();

// Sikring mod direkte adgang
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('Direct access is not allowed.');
}

// Test databaseforbindelsen (valgfrit)
try {
    $db = Database::getInstance()->getConnection();
    echo "Databaseforbindelse oprettet!";
} catch (PDOException $e) {
    echo "Databaseforbindelse fejlede: " . $e->getMessage();
}

