<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/init.php';

$current_page = $_REQUEST['page'] ?? 'homePage';
define('CURRENT_PAGE', $current_page);

$router = new Router();
$router->route($current_page);
