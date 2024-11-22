<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/init.php';


// Bestem hvilken side der skal loades
$page = $_GET['page'] ?? 'home'; // Standard til 'home', hvis ingen side er angivet

try {
    // Log routing-handlingen
    error_log("Routing til side: $page\n", 3, $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/logs/errors.log');

    // Instansier Router og kald dens route-metode
    $router = new Router();
    $router->route($page);

} catch (Exception $e) {
    // Fejlhåndtering for ukendte eller ugyldige sider
    error_log("Fejl i routing: " . $e->getMessage() . "\n", 3, $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/logs/errors.log');
    echo "<h1>En fejl opstod</h1><p>Kontakt administratoren, hvis fejlen fortsætter.</p>";
}



