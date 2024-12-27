<?php
// Aktivér fejlrapportering
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Test af stier og konfiguration
$expectedPath = __DIR__ . '/config/loadPages.php';
$calculatedPath = dirname(__DIR__, 2) . '/config/loadPages.php'; // Justér hvis nødvendigt

echo "<h1>Test af loadPages.php</h1>";

// Test 1: Findes den forventede fil?
if (file_exists($expectedPath)) {
    echo "<p style='color:green;'>Forventet fil findes: $expectedPath</p>";
} else {
    echo "<p style='color:red;'>Forventet fil mangler: $expectedPath</p>";
}

// Test 2: Findes den beregnede fil?
if (file_exists($calculatedPath)) {
    echo "<p style='color:green;'>Beregnet fil findes: $calculatedPath</p>";
} else {
    echo "<p style='color:red;'>Beregnet fil mangler: $calculatedPath</p>";
}

// Test 3: Læs filens indhold
if (file_exists($expectedPath)) {
    $config = require $expectedPath;

    if (is_array($config)) {
        echo "<p style='color:green;'>loadPages.php returnerer et gyldigt array.</p>";
    } else {
        echo "<p style='color:red;'>loadPages.php returnerer ikke et array.</p>";
    }
} else {
    echo "<p style='color:red;'>Kan ikke læse loadPages.php, fordi filen ikke findes.</p>";
}
