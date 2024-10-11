<?php
require_once 'constants.php';

try {
    // Opret en ny PDO-forbindelse
    $db = new PDO(DSN, DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Fang fejl i forbindelse og vis en fejlmeddelelse
    die("Databaseforbindelse fejlede: " . $e->getMessage());
}
?>

