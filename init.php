<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/core/autoloader.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/core/baseModel.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/config/connection.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/config/loadPages.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/core/utility.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/core/baseController.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/app/services/FileUploadService.php';


session_start();

// init.php - sikring mod direkte adgang
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('Direct access is not allowed.');
}
global $db;
try {
    $db = new PDO(DSN, DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Databaseforbindelse fejlede: " . $e->getMessage());
}


