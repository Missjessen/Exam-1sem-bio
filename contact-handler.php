<?php
require_once __DIR__ . '/init.php'; 


$contactController = new ContactController();


$response = $contactController->handleContactForm();

$_SESSION['contactMessage'] = $response;


error_log("Besked gemt i session: " . $_SESSION['contactMessage']);


header("Location: " . BASE_URL . "index.php?page=homePage#contact");
exit;


