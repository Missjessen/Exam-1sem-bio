<?php
// Aktivér fejlrapportering (kun til udvikling)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Definér den aktuelle side og slug
$current_page = $_GET['page'] ?? 'homePage';
$slug = $_GET['slug'] ?? null;
// Inkluder nødvendige filer og start session
require_once __DIR__ . '/init.php';

Security::startSession();




// Kendte ruter
$knownRoutes = [
    'homePage', 'movie_details', 'program', 'admin_dashboard',
    'admin_movie', 'admin_settings', 'admin_ManageUsers',
    'admin_bookings', 'review', 'login', 'logout', 'register',
    'admin_daily_showings', 'admin_parking'
];

// Beskyttede ruter (som kræver login)
$protectedUserRoutes = [''];
$protectedAdminRoutes = [''];

// Valider den aktuelle rute
if (!in_array($current_page, $knownRoutes)) {
    $current_page = '404';
}

// Gør den aktuelle side tilgængelig globalt
$GLOBALS['current_page'] = $current_page;

try {
    // Tjek adgangsbeskyttelse
    if (in_array($current_page, $protectedAdminRoutes)) {
        Security::checkLogin(true); // Kun admin
    } elseif (in_array($current_page, $protectedUserRoutes)) {
        Security::checkLogin(); // Almindelige brugere
    }

    // Router til at håndtere ruten
    $router = new Router();
    if ($current_page === 'movie_details' && !empty($slug)) {
        $router->route($current_page, ['slug' => $slug]);
    } else {
        $router->route($current_page);
    }
} catch (Exception $e) {
    // Global fejlhåndtering
    $errorController = new ErrorController();
    $errorController->show500("An error occurred: " . $e->getMessage());
}
