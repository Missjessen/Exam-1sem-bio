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
                // Opret en ny tidsplan for en film
                $data = [
                    'movie_id' => $_POST['movie_id'],
                    'schedule_date' => $_POST['schedule_date'],
                    'start_time' => $_POST['start_time']
                ];
                createContent($conn, 'Movie_Schedule', $data);
                break;

            case 'update':
                // Opdater en eksisterende tidsplan
                $id = intval($_POST['schedule_id']);
                $data = [
                    'movie_id' => $_POST['movie_id'],
                    'schedule_date' => $_POST['schedule_date'],
                    'start_time' => $_POST['start_time']
                ];
                updateContent($conn, 'Movie_Schedule', $id, $data);
                break;

            case 'delete':
                // Slet en tidsplan
                $id = intval($_POST['schedule_id']);
                deleteContent($conn, 'Movie_Schedule', $id);
                break;
        }
    }
}

// Hent og vis tidsplan for film
$result = $conn->query("SELECT * FROM Movie_Schedule");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div>Tidsplan ID: " . $row['schedule_id'] . ", Film ID: " . $row['movie_id'] . ", Dato: " . $row['schedule_date'] . ", Starttid: " . $row['start_time'] . "</div>";
    }
}

$conn->close();
?>