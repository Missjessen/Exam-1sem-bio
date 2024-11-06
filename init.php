<?php
if (session_status() === PHP_SESSION_NONE) {
   session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/core/autoloader.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/core/baseModel.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/config/connection.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/config/loadPages.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/logs/errors.log';
// init.php - sikring mod direkte adgang
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('Direct access is not allowed.');
}
// Opret forbindelse til databasen
try {
   $db = new PDO(DSN, DB_USER, DB_PASS);
   $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
   die("Databaseforbindelse fejlede: " . $e->getMessage());
}


?>