<?php 
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/config/connection.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/core/autoloader.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/init.php';


// Hent siden fra GET-parameteren
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Brug Router til at håndtere hvilken controller der skal kaldes
Router::route($page);
?>


<!-- Her kan du evt. inkludere en footer -->
</body>
</html>
