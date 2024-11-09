<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/init.php';


$cssFiles = [
    'home' => '/assets/css/homePage.css',
    'program' => '/assets/css/program.css',
    'movie' => '/assets/css/movie.css',
    'spots' => '/assets/css/spots.css',
    'tickets' => '/assets/css/tickets.css',
    'about' => '/assets/css/about.css',
];

if (array_key_exists($page, $cssFiles)) {
    $cssPath = $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio' . $cssFiles[$page];
} else {
    $cssPath = $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/assets/css/common.css';
}

echo "/* Trying to load: $cssPath */\n";

if (file_exists($cssPath)) {
    readfile($cssPath);
} else {
    echo "/* CSS file not found at: $cssPath */";
}
?>