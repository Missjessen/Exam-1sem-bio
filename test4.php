<?php
// Kræv nødvendige filer
require_once 'init.php';


// Simuler databaseforbindelse
$db = Database::getInstance()->getConnection();

// Simuler en test-router
$page = $_GET['page'] ?? 'homePage';

try {
    // Router logik
    Router::route($page);
} catch (Exception $e) {
    echo "Fejl: " . $e->getMessage();
}

// Testdata til mock af bookingen
if ($page === 'handle_booking') {
    $_POST = [
        'showing_id' => 1, // En eksisterende showing_id fra din database
        'spots' => 2       // Antal pladser at booke
    ];
}

if ($page === 'confirm_booking') {
    $_SESSION['user_id'] = 1; // Mock en bruger, der er logget ind
    $_SESSION['pending_booking'] = [
        'showing_id' => 1,
        'spots' => 2,
        'total_price' => 200,
        'movie_title' => 'Pulp Fiction',
        'show_date' => '2023-12-05',
        'show_time' => '19:00:00',
        'price_per_ticket' => 100
    ];
}
