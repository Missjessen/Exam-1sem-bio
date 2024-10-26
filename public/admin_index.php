<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/config/connection.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/core/autoloader.php';

require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/init.php';

// Din side-specifikke kode her...



// Hent siden fra GET-parameteren
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';


// Brug Router til at håndtere hvilken controller der skal kaldes
Router::route($page);
?>

