<?php

require_once 'init.php';

class ProfileTest {
    private $db;
    private $authController;
    private $bookingModel;

    public function __construct($db) {
        $this->db = $db;
        $this->authController = new AuthController($db);
        $this->bookingModel = new BookingModel($db);
    }

    public function testProfile($userId) {
        echo "<h1>Test: Profilside</h1>";

        // Hent brugerdata
        $user = $this->authController->getUserById($userId);
        if (!$user) {
            echo "<p style='color:red;'>Fejl: Bruger ikke fundet.</p>";
            return;
        }
        echo "<p style='color:green;'>Brugerdata hentet korrekt: " . htmlspecialchars($user['name']) . " (" . htmlspecialchars($user['email']) . ")</p>";

        // Hent brugerens bookinger
        $bookings = $this->bookingModel->getUserBookings($userId);
        if (empty($bookings)) {
            echo "<p style='color:red;'>Fejl: Ingen bookinger fundet for bruger.</p>";
            return;
        }
        echo "<p style='color:green;'>Bookinger hentet korrekt. Total: " . count($bookings) . "</p>";

        // Filtrer bookinger i aktuelle og tidligere
        $currentDate = date('Y-m-d H:i:s');
        $currentBookings = [];
        $pastBookings = [];

        foreach ($bookings as $booking) {
            $showDateTime = $booking['show_date'] . ' ' . $booking['show_time'];
            if ($showDateTime >= $currentDate) {
                $currentBookings[] = $booking;
            } else {
                $pastBookings[] = $booking;
            }
        }

        echo "<h2>Resultater:</h2>";

        // Test aktuelle bookinger
        if (!empty($currentBookings)) {
            echo "<h3>Aktuelle Bookinger</h3>";
            foreach ($currentBookings as $booking) {
                echo "<p>Film: " . htmlspecialchars($booking['movie_title']) . " | Dato: " . htmlspecialchars($booking['show_date']) . " | Tid: " . htmlspecialchars($booking['show_time']) . "</p>";
            }
        } else {
            echo "<p style='color:orange;'>Ingen aktuelle bookinger fundet.</p>";
        }

        // Test tidligere bookinger
        if (!empty($pastBookings)) {
            echo "<h3>Tidligere Bookinger</h3>";
            foreach ($pastBookings as $booking) {
                echo "<p>Film: " . htmlspecialchars($booking['movie_title']) . " | Dato: " . htmlspecialchars($booking['show_date']) . " | Tid: " . htmlspecialchars($booking['show_time']) . "</p>";
            }
        } else {
            echo "<p style='color:orange;'>Ingen tidligere bookinger fundet.</p>";
        }

        echo "<p style='color:green;'>Testen af profilsiden er fuldført.</p>";
    }
}

// Testkald
try {
    $db = Database::getInstance()->getConnection();

    // Test med en bruger-ID
    $userId = 1; // Skift denne til et valid bruger-ID fra din database
    $test = new ProfileTest($db);
    $test->testProfile($userId);

} catch (Exception $e) {
    echo "<p style='color:red;'>Test mislykkedes: " . $e->getMessage() . "</p>";
}

