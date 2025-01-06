<?php
require_once 'init.php';

function testBookingSystem($db) {
    $results = [];

    try {
        // 1. Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 2. Test: Login
        $authController = new AuthController($db);
        $email = "testuser@example.com";
        $password = "password123";

        if ($authController->loginUser($email, $password)) {
            $results[] = "Test Login: Success. Logged in as " . $_SESSION['user_name'];
        } else {
            $results[] = "Test Login: Failed. Invalid credentials.";
            throw new Exception("Login failed.");
        }

        // 3. Test: Opret booking
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['showing_id'] = 1; // Gyldigt ID fra databasen
        $_POST['spots'] = 2; // Antal pladser

        $bookingController = new BookingController($db);
        $bookingController->handleBooking();

        if (!empty($_SESSION['pending_booking'])) {
            $results[] = "Test Handle Booking: Success. Booking data saved in session.";
        } else {
            $results[] = "Test Handle Booking: Failed. No booking data in session.";
            throw new Exception("Booking data not saved.");
        }

        // 4. Test: Bekræft booking
        $bookingController->confirmBooking();
        $query = "SELECT * FROM bookings WHERE customer_id = :customer_id ORDER BY created_at DESC LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':customer_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();
        $confirmedBooking = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($confirmedBooking) {
            $results[] = "Test Confirm Booking: Success. Booking confirmed.";
        } else {
            $results[] = "Test Confirm Booking: Failed. Booking not saved to database.";
            throw new Exception("Booking not confirmed.");
        }

        // 5. Test: Logout
        $authController->logoutUser();
        if (!isset($_SESSION['user_id'])) {
            $results[] = "Test Logout: Success. User logged out.";
        } else {
            $results[] = "Test Logout: Failed. User still logged in.";
        }
    } catch (Exception $e) {
        $results[] = "Error: " . $e->getMessage();
    }

    return $results;
}

// Forbind til databasen
$db = Database::getInstance()->getConnection();

// Kør test
$testResults = testBookingSystem($db);

// Udskriv testresultater
echo "<h1>Testresultater</h1>";
echo "<ul>";
foreach ($testResults as $result) {
    echo "<li>$result</li>";
}
echo "</ul>";
?>
