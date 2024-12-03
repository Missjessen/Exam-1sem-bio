<?php
// Aktivér fejlrapportering
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Hent autoloader og andre nødvendige filer
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/init.php';


// Læs den SEO-venlige route fra URL'en
// Bestem hvilken side der skal loades
$page = $_GET['page'] ?? 'home'; // Standard til 'home', hvis ingen side er angivet

try {
    // Log routing-handlingen

    // Instansier Router og kald dens route-metode
    $router = new Router();
    $router->route($page);
    
} catch (Exception $e) {
    // Fejlhåndtering: Log fejl og vis en brugerdefineret fejlbesked
    error_log("Fejl i routeren: " . $e->getMessage());
    header("HTTP/1.0 500 Internal Server Error");
    echo "Der opstod en fejl. Prøv venligst igen senere.";
}



