<?php
require_once '../includes/functions.php';
require_once '../config.php';

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
                // Opret en ny parkeringsplads
                $data = [
                    'spot_number' => $_POST['spot_number'],
                    'price' => $_POST['price'],
                    'is_occupied' => $_POST['is_occupied']
                ];
                createContent($conn, 'Parking_Spots', $data);
                break;

            case 'update':
                // Opdater en eksisterende parkeringsplads
                $id = intval($_POST['spot_id']);
                $data = [
                    'spot_number' => $_POST['spot_number'],
                    'price' => $_POST['price'],
                    'is_occupied' => $_POST['is_occupied']
                ];
                updateContent($conn, 'Parking_Spots', $id, $data);
                break;

            case 'delete':
                // Slet en parkeringsplads
                $id = intval($_POST['spot_id']);
                deleteContent($conn, 'Parking_Spots', $id);
                break;
        }
    }
}

// Hent og vis parkeringspladsdata
$result = $conn->query("SELECT * FROM Parking_Spots");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div>Plads ID: " . $row['spot_id'] . ", Plads Nummer: " . $row['spot_number'] . ", Pris: " . $row['price'] . ", Optaget: " . ($row['is_occupied'] ? 'Ja' : 'Nej') . "</div>";
    }
}

$conn->close();
?>