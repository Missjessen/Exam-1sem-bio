
<?php
require_once 'constants.php';

try {
    $db = new PDO(DSN, DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Databaseforbindelse fejlede: " . $e->getMessage();
}
?>
