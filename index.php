<?php
 require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/init.php'; // Inkluder init.php med $db og autoloader
 if (isset($_GET['page'])) {
    $page = $_GET['page'];
    if ($page === 'movieDetails' && isset($_GET['slug'])) {
        $slug = $_GET['slug'];
        Router::route($page, $slug); // Send slug til routeren
    } else {
        Router::route($page);
    }
} else {
    Router::route('home'); // Standard side
}



