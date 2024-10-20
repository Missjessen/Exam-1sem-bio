<?php
// Sørg for at inkludere databaseforbindelsen én gang i dette script
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/includes/connection.php';


function loadPage($page) {
    // Kort over sider med deres specifikke fil- og CSS-stier
    $pages = [
        // public pages
        'homePage' => [
            'file' =>  'view/homePage.php',
            'css' => '/Exam-1sem-bio/assets/css/homePage.css'
        ],
        'program' => [
            'file' => 'view/program.php',
            'css' => '/Exam-1sem-bio/assets/css/program.css'
        ],
        'movie' => [
            'file' => 'view/movie.php',
            'css' => '/Exam-1sem-bio/assets/css/movie.css'
        ],
        'spots' => [
            'file' => 'view/spots.php',
            'css' => '/Exam-1sem-bio/assets/css/spots.css'
        ],
        'tickets' => [
            'file' =>  'view/tickets.php',
            'css' => '/Exam-1sem-bio/assets/css/tickets.css'
        ],
        'about' => [
            'file' => 'view/about.php',
            'css' => '/Exam-1sem-bio/assets/css/about.css'
        ],
          // admin pages 
          'dashboard' => [
            'file' =>  'admin/dashboard.php',
            'css' => '/Exam-1sem-bio/admin/admin_styles.css'
        ],
          'manage_pages' => [
            'file' => $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/admin/page/manage_pages.php',
            'css' => '/Exam-1sem-bio/admin/css/admin_styles.css'
        ],
        'admin_crud' => [
            'file' => $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/admin/admin_crud.php',
            'css' => '/Exam-1sem-bio/admin/css/admin_styles.css'
        ],
        'manage_user' => [
            'file' => $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/admin/manage_user.php',
            'css' => '/Exam-1sem-bio/admin/css/admin_styles.css'
        ],
        'settings' => [
            'file' => $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/admin/setting.php',
            'css' => '/Exam-1sem-bio/admin/css/admin_styles.css'
        ],
        'login' => [
            'file' => $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/auth/login.php',
            
        ],
        'admin-login' => [
            'file' => $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/auth/admin_login.php',
           
        ],
        'logout' => [
            'file' => $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/auth/logout.php',
           
        ],
    ];

    // Kontroller om siden findes i kortet, ellers brug 'home' som standard
    if (isset($pages[$page])) {
        $pageData = $pages[$page];
    } else {
        $pageData = $pages['homePage'];
    }

    // Inkluder CSS-fil
    if (isset($pageData['css'])) {
        echo '<link rel="stylesheet" href="' . $pageData['css'] . '">';
    } else {
        echo '<link rel="stylesheet" href="/Exam-1sem-bio/assets/css/common.css">'; // Fallback CSS hvis ingen CSS defineret
    }
    
    // Debug-kontrolpunkt for at tjekke om filstien er korrekt
    if (isset($pageData['file']) && !empty($pageData['file'])) {
        $filePath = $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/' . $pageData['file'];
        
        if (file_exists($filePath)) {
            include $filePath;
        } else {
            echo "<p>Fejl: Filen kunne ikke findes på stien: $filePath</p>";
        }
    } else {
        echo "<p>Fejl: Filstien er ikke korrekt defineret.</p>";
    }
}
?>
