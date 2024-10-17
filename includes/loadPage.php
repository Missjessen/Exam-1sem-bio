<?php
// Sørg for at inkludere databaseforbindelsen én gang i dette script
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/includes/connection.php';


function loadPage($page) {
    // Kort over sider med deres specifikke fil- og CSS-stier
    $pages = [
        'home' => [
            'file' => $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/view/homePage.php',
            'css' => '/Exam-1sem-bio/assets/css/homePage.css'
        ],
        'program' => [
            'file' => $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/view/program.php',
            'css' => '/Exam-1sem-bio/assets/css/program.css'
        ],
        'movie' => [
            'file' => $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/view/movie.php',
            'css' => '/Exam-1sem-bio/assets/css/movie.css'
        ],
        'spots' => [
            'file' => $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/view/spots.php',
            'css' => '/Exam-1sem-bio/assets/css/spots.css'
        ],
        'tickets' => [
            'file' => $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/view/tickets.php',
            'css' => '/Exam-1sem-bio/assets/css/tickets.css'
        ],
        'about' => [
            'file' => $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/view/about.php',
            'css' => '/Exam-1sem-bio/assets/css/about.css'
        ],
    ];

    $pageData = $pages[$page] ?? $pages['home'];

    echo '<link rel="stylesheet" href="' . $pageData['css'] . '">';
    
    // Inkludér siden
    include $pageData['file'];
}
?>
