<?php 
require_once __DIR__ . '/init.php';

try {
    $pageController = new PageController();
    $pageController->showPage('admin_movie');
    echo "Admin Movie Page Loaded Successfully.";
} catch (Exception $e) {
    echo "Fejl: " . $e->getMessage();
}