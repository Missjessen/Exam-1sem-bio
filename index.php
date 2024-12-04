<?php
// Aktivér fejlrapportering
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once 'init.php';



// Definér den aktuelle side
$current_page = $_GET['page'] ?? 'home';

// Kendte ruter
$knownRoutes = [
    'homePage', 'about', 'program', 'admin_dashboard',
    'admin_movie', 'admin_settings', 'admin_ManageUsers',
    'book', 'review', 'login', 'logout', 'register'
];

// Hvis siden ikke findes i de kendte ruter, sæt til '404'
if (!in_array($current_page, $knownRoutes)) {
    $current_page = '404';
}

// Gør den globalt tilgængelig
$GLOBALS['current_page'] = $current_page;

try {
    // Log routing-handlingen

    // Instansier Router og kald dens route-metode
    $router = new Router();
    $router->route($current_page);
    
} catch (Exception $e) {
    // Fejlhåndtering: Log fejl og vis en brugerdefineret fejlbesked
    error_log("Fejl i routeren: " . $e->getMessage());
    header("HTTP/1.0 500 Internal Server Error");
    echo "Der opstod en fejl. Prøv venligst igen senere.";
}



