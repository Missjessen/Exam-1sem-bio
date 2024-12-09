<?php
// Aktivér fejlrapportering
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inkluder nødvendige filer for at starte session og sikkerhed
require_once __DIR__ . '/init.php';

// Start session via Security-klasse
Security::startSession();

// Definér den aktuelle side og slug
$current_page = $_REQUEST['page'] ?? 'homePage';

$slug = $_GET['slug'] ?? null;


// Kendte ruter
$knownRoutes = [
    'homePage', 'movie_details', 'program', 'admin_dashboard',
    'admin_movie', 'admin_settings', 'admin_ManageUsers',
    'admin_bookings', 'review', 'login', 'logout', 'register', 'admin_daily_showings', 'admin_parking'
];

// Beskyttede ruter (som kræver login)
$protectedUserRoutes = ['book', 'review'];
$protectedAdminRoutes = [''];

// Hvis siden ikke findes i de kendte ruter, sæt til '404'
if (!in_array($current_page, $knownRoutes)) {
    $current_page = '404';
}

// Gør den globalt tilgængelig
$GLOBALS['current_page'] = $current_page;

try {
    // Tjek adgangsbeskyttelse
    if (in_array($current_page, $protectedAdminRoutes)) {
        Security::checkLogin(true); // Adminbeskyttelse
    } elseif (in_array($current_page, $protectedUserRoutes)) {
        Security::checkLogin(); // Brugerbeskyttelse
    }

    // Instansier Router og kald dens route-metode
    $router = new Router();
    if ($current_page === 'movie_details' && !empty($_GET['slug'])) {
        $slug = $_GET['slug'];
        $router->route($current_page, ['slug' => $slug]);
    } elseif ($current_page === 'movie_details') {
        throw new Exception("Slug mangler i URL'en.");
    } else {
        $router->route($current_page);
    }
    
} catch (Exception $e) {
    // Global fejlhåndtering
    $errorController = new ErrorController();
    $errorController->show500("An error occurred: " . $e->getMessage());
}
