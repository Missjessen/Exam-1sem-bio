<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/config/connection.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/core/autoloader.php';

// Forbind til databasen
$conn = new mysqli($db_host, DB_USER, DB_PASS, $db_dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Håndter forskellige CRUD-operationer baseret på handling
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        switch ($action) {
            case 'create':
                // Opret en ny booking
                $data = [
                    'customer_id' => $_POST['customer_id'],
                    'spot_id' => $_POST['spot_id'],
                    'booking_date' => $_POST['booking_date'],
                    'status' => $_POST['status']
                ];
                createContent($conn, 'Bookings', $data);
                break;

            case 'update':
                // Opdater en eksisterende booking
                $id = intval($_POST['booking_id']);
                $data = [
                    'customer_id' => $_POST['customer_id'],
                    'spot_id' => $_POST['spot_id'],
                    'booking_date' => $_POST['booking_date'],
                    'status' => $_POST['status']
                ];
                updateContent($conn, 'Bookings', $id, $data);
                break;

            case 'delete':
                // Slet en booking
                $id = intval($_POST['booking_id']);
                deleteContent($conn, 'Bookings', $id);
                break;
        }
    }
}

// Hent og vis bookingdata
$result = $conn->query("SELECT * FROM Bookings");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div>Booking ID: " . $row['booking_id'] . ", Kunde ID: " . $row['customer_id'] . ", Plads ID: " . $row['spot_id'] . ", Status: " . $row['status'] . "</div>";
    }
}

$conn->close();
?>