<?php
// Start debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Simuler en slug for test
$testSlug = $_GET['slug'] ?? null;

// Vis debug-information
echo "<h1>Slug Test</h1>";

if ($testSlug) {
    echo "<p>Slug modtaget: <strong>" . htmlspecialchars($testSlug, ENT_QUOTES, 'UTF-8') . "</strong></p>";
} else {
    echo "<p style='color:red;'>Slug mangler! Tjek din URL.</p>";
}

// Test generering af en URL
if (!function_exists('currentPageURL')) {
    function currentPageURL($page, $additionalParams = []) {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? "https" : "http");
        $host = $_SERVER['HTTP_HOST'];
        $uri = strtok($_SERVER['REQUEST_URI'], '?'); // Fjern eksisterende query-parametre

        // Opdater query-parametre
        $queryParams = array_merge(['page' => $page], $additionalParams);

        // Generér ny URL
        return $protocol . "://" . $host . $uri . '?' . http_build_query($queryParams);
    }
}

// Test med en dummy slug
$generatedUrl = currentPageURL('movie_details', ['slug' => 'test-slug']);
echo "<p>Genereret URL: <a href='$generatedUrl'>$generatedUrl</a></p>";

// Test data-output for debugging
echo "<h2>Debug Information:</h2>";
echo "<pre>";
var_dump([
    'REQUEST_URI' => $_SERVER['REQUEST_URI'],
    'GET Parameters' => $_GET,
    'Generated URL' => $generatedUrl
]);
echo "</pre>";

// Test databaseforbindelse
echo "<h2>Database Test:</h2>";

try {
    require_once 'init.php'; // Sørg for at inkludere korrekt databaseinitialisering
    $db = Database::getInstance()->getConnection();

    // Test en simpel forespørgsel
    $query = $db->prepare("SELECT slug FROM movies LIMIT 5");
    $query->execute();
    $movies = $query->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($movies)) {
        echo "<p>Film hentet fra databasen:</p>";
        echo "<ul>";
        foreach ($movies as $movie) {
            echo "<li>" . htmlspecialchars($movie['slug'], ENT_QUOTES, 'UTF-8') . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p style='color:red;'>Ingen film fundet i databasen.</p>";
    }
} catch (Exception $e) {
    echo "<p style='color:red;'>Fejl i databaseforbindelsen: " . $e->getMessage() . "</p>";
}

echo "<h2>Afsluttet testscript</h2>";
