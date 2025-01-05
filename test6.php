<?php 
session_start();

// Simuler POST-anmodning
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST['showing_id'] = 1; // Test med gyldigt visnings-ID
$_POST['spots'] = 2; // Test med antal pladser

// Indsæt korrekte databaseforbindelsesoplysninger
try {
    $db = new PDO('mysql:host=localhost;dbname=cjsfkt3sf_cruisenightscinema', 'cjsfkt3sf_cruisenightscinema', '123456');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Inkluder nødvendige filer
    require_once 'BookingController.php'; // Juster stien efter behov

    $controller = new BookingController($db);

    // Test BookingController
    $controller->handleBooking();
    echo "Booking oprettet succesfuldt: ";
    print_r($_SESSION['pending_booking']);
} catch (PDOException $e) {
    echo "Fejl ved databaseforbindelse: " . $e->getMessage();
} catch (Exception $e) {
    echo "Fejl: " . $e->getMessage();
}
