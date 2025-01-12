<?php
require_once __DIR__ . '/init.php';


$current_page = $_GET['page'] ?? 'homePage';
define('CURRENT_PAGE', $current_page);

try {
    $router = new Router();
    $router->route($current_page);
} catch (Exception $e) {
    error_log("Fejl i index.php: " . $e->getMessage());
    http_response_code(500);
    include __DIR__ . '/app/view/Error/500.php';
}