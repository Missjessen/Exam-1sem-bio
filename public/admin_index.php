

<?php
session_start();

// Inkluder databaseforbindelse
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/config/connection.php';

// Inkluder autoloader, der kan håndtere de fleste klasser automatisk
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/core/autoloader.php';

// Inkluder eventuelle andre nødvendige konfigurationsfiler
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/init.php';

// Hvis Router-klassen ikke bliver autoloadet, inkluder den manuelt
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/app/services/router.php';

// Hent siden fra GET-parameteren
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Kald routen ved hjælp af Router-klassen
Router::route($page);
?>
