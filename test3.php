<?php
// Start debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'init.php';

// Test 1: Registrering
$response = $authController->registerUser('Test User', 'test@example.com', 'password123');
assert($response === true, "Registrering fejlede.");

// Test 2: Login
$loginResponse = $authController->loginUser('test@example.com', 'password123');
assert(isset($_SESSION['user_id']), "Login fejlede.");

// Test 3: Start Booking
$_POST['showing_id'] = 1;
$_POST['spots'] = 2;
$bookingController->handleBooking();
assert(isset($_SESSION['pending_booking']), "Booking blev ikke startet.");

// Test 4: Bekræft Booking
$bookingController->confirmBooking();
assert(!isset($_SESSION['pending_booking']), "Booking blev ikke bekræftet.");
