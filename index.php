<?php
// Aktivér fejlrapportering (kun til udvikling)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inkluder nødvendige filer
require_once __DIR__ . '/init.php';


define('CURRENT_PAGE', $current_page);
// Start session
//Security::startSession();
$current_page = $_REQUEST['page'] ?? 'homePage';





    $router = new Router();
    $router->route($current_page);