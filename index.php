<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/init.php'; // Inkluder init.php med $db og autoloader

// Bestem hvilken side der skal vises, baseret på URL'en
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Brug Router til at håndtere visning af siden
Router::route($page);
?>




