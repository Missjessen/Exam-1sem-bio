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
        $email = "testuser@example.com"; // Ændr til en eksisterende brugers email
        $password = "password123"; // Ændr til brugerens password

        if ($authController->loginUser($email, $password)) {
            $results[] = "Test Login: Success. Logged in as " . $_SESSION['user_name'];
        } else {
            $results[] = "Test Login: Failed. Invalid credentials.";
            throw new Exception("Login failed.");
        }

        // 3. Test: Hent en visning til booking
        $bookingModel = new BookingModel($db);
        $showingId = 1; // Ændr til en gyldig visnings-ID
        $showingDetails = $bookingModel->getShowingDetails($showingId);

        if ($showingDetails) {
            $results[] = "Test Hent Visning: Success. Found showing with ID $showingId.";
        } else {
            $results[] = "Test Hent Visning: Failed. Could not find showing.";
            throw new Exception("Showing not found.");
        }

        // 4. Test: Opret booking (Handle Booking)
        $_POST['showing_id'] = $showingId;
        $_POST['spots'] = 2; // Ændr til antal pladser
        $bookingController = new BookingController($db);
        $bookingController->handleBooking();

        if (!empty($_SESSION['pending_booking'])) {
            $results[] = "Test Handle Booking: Success. Booking data saved in session.";
        } else {
            $results[] = "Test Handle Booking: Failed. No booking data in session.";
            throw new Exception("Booking data not saved.");
        }

        // 5. Test: Bekræft booking
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

        // 6. Test: Hent kvittering
        $bookingController->showReceipt();
        if (!empty($_SESSION['pending_booking'])) {
            $results[] = "Test Show Receipt: Success. Receipt data displayed.";
        } else {
            $results[] = "Test Show Receipt: Failed. No data in session for receipt.";
        }

        // 7. Test: Logout
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
