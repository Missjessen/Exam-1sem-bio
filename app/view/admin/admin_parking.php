<?php
// Aktivér fejlrapportering for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inkludér init.php for at håndtere alle nødvendige initialiseringer
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/init.php';

try {
    // Test databaseforbindelsen via singleton
    $db = Database::getInstance()->getConnection();
    echo "Databaseforbindelse er oprettet!<br>";

    // Test en simpel forespørgsel
    $sql = "SELECT * FROM movies LIMIT 5";
    $stmt = $db->query($sql);
    $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($movies) {
        echo "Data hentet fra tabellen 'movies':<br>";
        echo "<pre>";
        print_r($showings); // Vis de hentede data
        echo "</pre>";
    } else {
        echo "Ingen data fundet i tabellen 'movies'.<br>";
    }
} catch (PDOException $e) {
    error_log("Databaseforbindelse eller forespørgsel fejlede: " . $e->getMessage());
    echo "Databaseforbindelse eller forespørgsel fejlede. Tjek logfilerne for detaljer.";
} catch (Exception $e) {
    error_log("Generel fejl: " . $e->getMessage());
    echo "Generel fejl opstod. Tjek logfilerne for detaljer.";
}
echo "Rewrite fungerer!";
error_log("Ruter side: $page");

error_log("Aktuel rute: $page");