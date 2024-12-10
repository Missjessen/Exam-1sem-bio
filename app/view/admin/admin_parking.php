<?php
require_once dirname(__DIR__, 3) . '/init.php';

try {
    // Forsøg at instantiere BaseController
    $controller = new \core\BaseController(null); // Sørg for korrekt namespace
    echo "BaseController indlæst korrekt!";
} catch (Exception $e) {
    echo "Fejl: " . $e->getMessage();
}
