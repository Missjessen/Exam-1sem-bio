<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/includes/functions.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/includes/connection.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/includes/constants.php';

// Forbind til databasen
$conn = new mysqli($db_host, DB_USER, DB_PASS, $db_dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Håndter forskellige CRUD-operationer baseret på handling
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        // Filstien til hvor vi gemmer billeder
        $upload_dir = '../uploads/';

        switch ($action) {
            case 'create':
                // Opret en ny film
                $poster_path = '';
                if (isset($_FILES['poster']) && $_FILES['poster']['error'] === UPLOAD_ERR_OK) {
                    $tmp_name = $_FILES['poster']['tmp_name'];
                    $poster_name = basename($_FILES['poster']['name']);
                    $poster_path = $upload_dir . $poster_name;

                    // Flyt filen til uploads-mappen
                    move_uploaded_file($tmp_name, $poster_path);
                }

                $data = [
                    'age_limit' => $_POST['age_limit'],
                    'title' => $_POST['title'],
                    'director' => $_POST['director'],
                    'release_year' => $_POST['release_year'],
                    'runtime' => $_POST['runtime'],
                    'description' => $_POST['description'],
                    'poster' => $poster_path // Gem stien til billedet
                ];
                createContent($conn, 'Movies', $data);
                break;

            case 'update':
                // Opdater en eksisterende film
                $id = intval($_POST['movie_id']);
                $poster_path = '';
                if (isset($_FILES['poster']) && $_FILES['poster']['error'] === UPLOAD_ERR_OK) {
                    $tmp_name = $_FILES['poster']['tmp_name'];
                    $poster_name = basename($_FILES['poster']['name']);
                    $poster_path = $upload_dir . $poster_name;

                    // Flyt filen til uploads-mappen
                    move_uploaded_file($tmp_name, $poster_path);
                }

                $data = [
                    'age_limit' => $_POST['age_limit'],
                    'title' => $_POST['title'],
                    'director' => $_POST['director'],
                    'release_year' => $_POST['release_year'],
                    'runtime' => $_POST['runtime'],
                    'description' => $_POST['description']
                ];
                if ($poster_path) {
                    $data['poster'] = $poster_path; // Kun opdatér billede, hvis der er et nyt upload
                }
                updateContent($conn, 'Movies', $id, $data);
                break;

            case 'delete':
                // Slet en film
                $id = intval($_POST['movie_id']);
                deleteContent($conn, 'Movies', $id);
                break;

            default:
                echo "Ugyldig handling!";
        }
      
    }
}

// Hent og vis filmdata
$result = $conn->query("SELECT * FROM Movies");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div>
        <img src='" . $row['poster'] . "' alt='Film Plakat' style='width: 100px; height: auto;'>
        Film ID: " . $row['movie_id'] . ",age_limit:" . $row['age_limit'] .  ", Titel: " . $row['title'] .  ",director:" . $row['director'] .  ",release_year: " . $row['release_year'] .  ",runtime: " . $row['runtime'] .  ",description:" . $row['description'] ."</div>";
    }
}

$conn->close();
?>