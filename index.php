<?php
// Aktivér fejlrapportering (kun til udvikling)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inkluder nødvendige filer
require_once __DIR__ . '/init.php';
require_once __DIR__ . '/app/models/Security.php';


// Start session
Security::startSession();

// Definér den aktuelle side
$current_page = $_GET['page'] ?? 'homePage';

// Gør den aktuelle side globalt tilgængelig
define('CURRENT_PAGE', $current_page);

try {
    // Tjek adgangsbeskyttelse, hvis det er nødvendigt
    if ($current_page === 'admin_dashboard' || $current_page === 'admin_settings') {
        Security::checkLogin(true); // Admin-sider
    } elseif ($current_page === 'profile' || $current_page === 'review') {
        Security::checkLogin(); // Bruger-sider
    }

    // Routeren håndterer ruterne
    $router = new Router();
    $router->route($current_page);
} catch (Exception $e) {
    // Global fejlhåndtering
    echo "Der opstod en fejl: " . $e->getMessage();
    exit();
}
