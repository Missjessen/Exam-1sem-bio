<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/init.php'; // Inkluder init.php med $db og autoloader

// standardværdien til 'home'
$page = $_GET['page'] ?? 'home'; // Brug standardværdi 'home' hvis parameteren mangler

//  hvilken side der bliver kaldt
error_log("Routing til side: $page", 3, $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/logs/errors.log');

// Kald Router:
Router::route($page);





