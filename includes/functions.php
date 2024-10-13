
<?php

function loadPage($page) {
    // Map of pages to files and styles
    $pages = [
        'home' => [
            'file' => 'homePage.php',
            'css' => 'homePage.css'
        ],
        'movie' => [
            'file' => 'movieView/movie.php',
            'css' => 'movie.css'
        ],
        'spots' => [
            'file' => 'spots/spots.php',
            'css' => 'spots.css'
        ],
        'about' => [
            'file' => 'about.php',
            'css' => 'common.css'
        ]
    ];
    
    // Check if page exists in the map, otherwise default to 'home'
    $pageData = $pages[$page] ?? $pages['home'];

    // Include the CSS file
    echo '<link rel="stylesheet" href="/Exam-1sem-bio/css/' . $pageData['css'] . '">';

    // Include the page file
    include $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/' . $pageData['file'];
}
?>










