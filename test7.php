<?php
require_once 'init.php';

// Sørg for, at sessionen er startet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function printTestResult($testName, $result) {
    echo "<h2>{$testName}</h2>";
    if ($result) {
        echo "<p style='color:green;'>Success</p>";
    } else {
        echo "<p style='color:red;'>Failure</p>";
    }
}

// Databaseforbindelse
$db = Database::getInstance()->getConnection();

// AuthController test
$authController = new AuthController($db);

// Test 1: Registrering
try {
    $name = "Test User";
    $email = "testuser1234@example.com";
    $password = "password123";
    $result = $authController->registerUser($name, $email, $password);
    printTestResult("Test 1: Registrering", $result);
} catch (Exception $e) {
    echo "<h2>Test 1: Registrering</h2>";
    echo "<p style='color:red;'>Fejl: {$e->getMessage()}</p>";
}

// Test 2: Login med korrekt data
try {
    $email = "testuser@example.com";
    $password = "password123";
    $result = $authController->loginUser($email, $password);
    printTestResult("Test 2: Login med korrekt data", $result);
} catch (Exception $e) {
    echo "<h2>Test 2: Login med korrekt data</h2>";
    echo "<p style='color:red;'>Fejl: {$e->getMessage()}</p>";
}

// Test 3: Login med forkert data
try {
    $email = "wronguser@example.com";
    $password = "wrongpassword";
    $result = $authController->loginUser($email, $password);
    printTestResult("Test 3: Login med forkert data", !$result);
} catch (Exception $e) {
    echo "<h2>Test 3: Login med forkert data</h2>";
    echo "<p style='color:red;'>Fejl: {$e->getMessage()}</p>";
}

// Test 4: Start en booking
try {
    $showingId = 1; // Sørg for, at denne ID eksisterer i showings-tabellen
    $spots = 2;

    $_POST['showing_id'] = $showingId;
    $_POST['spots'] = $spots;

    // Simuler handleBooking
    $bookingController = new BookingController($db);
    $bookingController->handleBooking();

    if (isset($_SESSION['pending_booking'])) {
        printTestResult("Test 4: Start en booking", true);
        echo "<pre>" . print_r($_SESSION['pending_booking'], true) . "</pre>";
    } else {
        printTestResult("Test 4: Start en booking", false);
    }
} catch (Exception $e) {
    echo "<h2>Test 4: Start en booking</h2>";
    echo "<p style='color:red;'>Fejl: {$e->getMessage()}</p>";
}

// Test 5: Bekræft booking
try {
    if (!isset($_SESSION['user_id'])) {
        echo "<h2>Test 5: Bekræft booking</h2>";
        echo "<p style='color:red;'>Fejl: Brugeren er ikke logget ind.</p>";
    } else {
        $bookingController->confirmBooking();
        printTestResult("Test 5: Bekræft booking", true);
    }
} catch (Exception $e) {
    echo "<h2>Test 5: Bekræft booking</h2>";
    echo "<p style='color:red;'>Fejl: {$e->getMessage()}</p>";
}

// Test 6: Profil-side (vis bookinger)
try {
    if (!isset($_SESSION['user_id'])) {
        echo "<h2>Test 6: Profil-side</h2>";
        echo "<p style='color:red;'>Fejl: Brugeren er ikke logget ind.</p>";
    } else {
        $pageController = new PageController();
        $pageController->profile();

        printTestResult("Test 6: Profil-side", true);
    }
} catch (Exception $e) {
    echo "<h2>Test 6: Profil-side</h2>";
    echo "<p style='color:red;'>Fejl: {$e->getMessage()}</p>";
}

?>
