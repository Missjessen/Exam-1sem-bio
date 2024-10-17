<?php
require_once 'constants.php';

try {
    $db = new PDO(DSN, DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Forbindelsen til databasen blev oprettet!";
} catch (PDOException $e) {
    die("Databaseforbindelse fejlede: " . $e->getMessage());
}

?>

