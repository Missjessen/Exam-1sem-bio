<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/core/autoloader.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/core/baseModel.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/config/connection.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/config/loadPages.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/logs/error.log';
// init.php - sikring mod direkte adgang
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('Direct access is not allowed.');
}



?>