<?php
session_start();
require_once 'init.php'; // Sørg for, at alle nødvendige klasser og konfigurationer er inkluderet

// Opret databaseforbindelse
$db = Database::getInstance()->getConnection();

// AuthController til login og registrering
$authController = new AuthController($db);

// BookingController til bookinghåndtering
$bookingController = new BookingController($db);

// PageLoader til at rendere sider
$pageLoader = new PageLoader($db);

// Test cases
echo "<h2>Test 1: Registrering</h2>";
$authController->registerUser("Test Bruger", "testuser@example.com", "password123");

echo "<h2>Test 2: Login med korrekt data</h2>";
$authController->loginUser("testuser@example.com", "password123");

echo "<h2>Test 3: Login med forkert data</h2>";
$authController->loginUser("testuser@example.com", "wrongpassword");

echo "<h2>Test 4: Start en booking</h2>";
$_POST['showing_id'] = 1; // Testvisning
$_POST['spots'] = 2; // To pladser
$bookingController->handleBooking();

echo "<h2>Test 5: Vis bookingoversigt</h2>";
if (isset($_SESSION['pending_booking'])) {
    print_r($_SESSION['pending_booking']);
} else {
    echo "Ingen booking fundet.";
}

echo "<h2>Test 6: Bekræft booking</h2>";
$bookingController->confirmBooking();

echo "<h2>Test 7: Se profil</h2>";
$pageLoader->renderPage('profile', [
    'bookings' => $bookingController->bookingModel->getBookingsByUser($_SESSION['user_id'] ?? 0)
]);

echo "<h2>Test 8: Logout</h2>";
$authController->logoutUser();
