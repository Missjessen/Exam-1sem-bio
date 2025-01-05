<?php 
session_start();

// Simuler POST-anmodning
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST['showing_id'] = 1; // Test med gyldigt visnings-ID
$_POST['spots'] = 2; // Test med antal pladser

// Inkluder nødvendige filer
require_once 'BookingController.php'; // Juster stien efter behov

$db = new PDO('mysql:host=localhost;dbname=cjsfkt3sf_cruisenightscinema', 'username', 'password'); // Opdater med korrekte DB-detaljer
$controller = new BookingController($db);

try {
    $controller->handleBooking();
    echo "Booking oprettet succesfuldt: ";
    print_r($_SESSION['pending_booking']);
} catch (Exception $e) {
    echo "Fejl: " . $e->getMessage();
}
