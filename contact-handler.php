<?php
require_once __DIR__ . '/init.php'; 

// Initialiser controlleren
$contactController = new ContactController();

// Håndter kontaktformularen
$response = $contactController->handleContactForm();

// Gem feedback i sessionen
$_SESSION['contactMessage'] = $response;

// Debugging
error_log("Besked gemt i session: " . $_SESSION['contactMessage']);

// Omdiriger til forsiden med hash for kontaktsektionen
header("Location: " . BASE_URL . "index.php?page=homePage#contact");
exit;


