<?php


// Tjek brugerens rolle for at afgøre, om det er admin eller bruger
session_start();
//$isAdmin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Definer CSS-filer til bruger og admin sider
$cssFiles = [
    'home' => '/assets/css/homePage.css',
    'program' => '/assets/css/program.css',
    'movie' => '/assets/css/movie.css',
    'spots' => '/assets/css/spots.css',
    'tickets' => '/assets/css/tickets.css',
    'about' => '/assets/css/about.css',
    'admin_movie' => '/assets/css/admin_movie.css',
    'dashboard' => '/assets/css/admin_styles.css',
    'settings' => '/assets/css/admin_styles.css',
    'manage_user' => '/assets/css/admin_styles.css',
];

// Tjek om siden findes i CSS-filer arrayet
if (array_key_exists($page, $cssFiles)) {
    $cssPath = $cssFiles[$page];
} else {
    // Brug en standard CSS, hvis siden ikke findes
    $cssPath = '/assets/css/common.css';
}

// Returnér stien til CSS-filen baseret på brugerrollen
if ($isAdmin && strpos($page, 'admin') !== false) {
    // Hvis brugeren er admin og siden er en admin-side
    echo '/assets/css/admin_styles.css';
} else {
    // Hvis det er en brugerside
    echo $cssPath;
}

?> 