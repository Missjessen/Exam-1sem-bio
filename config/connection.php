<?php
$constantsPath = __DIR__ . '/../core/constants.php';
if (!is_readable($constantsPath)) {
    die("Filen constants.php kunne ikke findes på stien: $constantsPath");
}
require_once $constantsPath;
try {
    $db = new PDO(DSN, DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Fjern echo-linjen, da vi ikke ønsker at vise en meddelelse hver gang forbindelsen oprettes.
} catch (PDOException $e) {
    die("Databaseforbindelse fejlede: " . $e->getMessage());
}



