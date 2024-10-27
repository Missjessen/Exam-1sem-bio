<?php

// config/loadPages.php - Konfigurationsfil for sider
return [
    'default_css' => '/Exam-1sem-bio/assets/css/common.css',
    'pages' => [
        'home' => ['view' => 'user/homePage.php', 'css' => '/assets/css/homePage.css'],
        'program' => ['view' => 'user/program.php', 'css' => '/assets/css/program.css'],
        'movie' => ['view' => 'user/movie.php', 'css' => '/assets/css/movie.css'],
        'spots' => ['view' => 'user/spots.php', 'css' => '/assets/css/spots.css'],
        'tickets' => ['view' => 'user/tickets.php', 'css' => '/assets/css/tickets.css'],
        'about' => ['view' => 'user/about.php', 'css' => '/assets/css/about.css'],
        'dashboard' => ['view' => 'admin/dashboard.php', 'css' => '/assets/css/admin_styles.css'],
        'manage_pages' => ['view' => 'admin/manage_pages.php', 'css' => '/assets/css/admin_styles.css'],
       
'admin_movie' => ['view' => 'admin/admin_movie.php', 'css' => '/assets/css/admin_movie.css'],

        'manage_user' => ['view' => 'admin/manage_user.php', 'css' => '/assets/css/admin_styles.css'],
        'settings' => ['view' => 'admin/settings.php', 'css' => '/assets/css/admin_styles.css'],
        'login' => ['view' => 'auth/login.php'],
        'admin-login' => ['view' => 'auth/admin_login.php'],
        'logout' => ['view' => 'auth/logout.php'],
    ],
];
?>