<?php
// Aktivér fejlrapportering (kun til udvikling)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inkluder nødvendige filer
require_once __DIR__ . '/init.php';



// Start session
//Security::startSession();
$current_page = $_GET['page'] ?? 'homePage';


define('CURRENT_PAGE', $current_page);


    $router = new Router();
    $router->route($current_page);