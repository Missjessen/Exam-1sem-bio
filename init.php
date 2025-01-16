<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


define('BASE_URL', rtrim((isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']), '/') . '/');



function currentPageURL($page, $additionalParams = []) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? "https" : "http");
    $host = $_SERVER['HTTP_HOST'];

    
    $queryParams = ['page' => $page];
    
    
    foreach ($additionalParams as $key => $value) {
        $queryParams[$key] = $value;
    }

   
    $queryString = http_build_query($queryParams);
    return $protocol . '://' . $host . '/index.php?' . $queryString;
}


require_once __DIR__ . '/core/autoLoader.php';
try {
    // Initialiser databaseforbindelsen
    $db = Database::getInstance()->getConnection();
    error_log("Databaseforbindelse er klar.");

    // Funktion til at sætte MySQL-tidszonen til PHP-tidszonen
    function setMySQLTimeZoneToPHPLocal(PDO $db) {
        $phpTimeZone = date_default_timezone_get(); 
        try {
            $db->exec("SET time_zone = '$phpTimeZone'");
            error_log("MySQL tidszone sat til PHP-tidszone: $phpTimeZone");
        } catch (PDOException $e) {
            error_log("Kunne ikke matche MySQL tidszone med PHP-tidszone: " . $e->getMessage());
            throw new Exception("Kunne ikke matche MySQL tidszone med PHP-tidszone.");
        }
    }

    // Kald funktionen for at sætte tidszonen
    setMySQLTimeZoneToPHPLocal($db);

} catch (Exception $e) {
    error_log("Fejl i databaseforbindelsen: " . $e->getMessage());
    die("Kunne ikke oprette databaseforbindelse.");
}