<?php
// Aktivér fejlrapportering
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inkluder nødvendige filer for at starte session og sikkerhed
require_once __DIR__ . '/init.php';

// Start session via Security-klasse
Security::startSession();

// Definér den aktuelle side
$current_page = $_GET['page'] ?? 'home';

// Kendte ruter (med ruter, der kræver loginbeskyttelse)
$knownRoutes = [
    'homePage', 'about', 'program', 'admin_dashboard',
    'admin_movie', 'admin_settings', 'admin_ManageUsers',
    'book', 'review', 'login', 'logout', 'register', 'admin_daily_showings', 'admin_parking'];

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
    // Log routing-handlingen
    // Tjek adgangsbeskyttelse
    if (in_array($current_page, $protectedAdminRoutes)) {
        Security::checkLogin(true); // Admin beskyttelse
    } elseif (in_array($current_page, $protectedUserRoutes)) {
        Security::checkLogin(); // Brugerbeskyttelse
    }

    // Instansier Router og kald dens route-metode
    $router = new Router();
    $router->route($current_page);
    
} catch (Exception $e) {
    // Global fejlhåndtering
    $errorController = new ErrorController();
    $errorController->show500("An error occurred.");
}
