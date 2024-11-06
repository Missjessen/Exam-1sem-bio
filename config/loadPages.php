<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/init.php'; // Inkluder init.php med $db og autoloader

// config/loadPages.php - Konfigurationsfil for sider
return [
    'default_css' => '/Exam-1sem-bio/assets/css/common.css',
    'pages' => [
        'home' => ['view' => 'app/view/user/homePage.php', 'css' => '/assets/css/homePage.css'],
        'program' => ['view' => 'app/view/user/program.php', 'css' => '/assets/css/program.css'],
        'movie' => ['view' => 'app/view/user/movie.php', 'css' => '/assets/css/movie.css'],
        'spots' => ['view' => 'app/view/user/spots.php', 'css' => '/assets/css/spots.css'],
        'tickets' => ['view' => 'app/view/user/tickets.php', 'css' => '/assets/css/tickets.css'],
        'about' => ['view' => 'app/view/user/about.php', 'css' => '/assets/css/about.css'],
        'movie_details' => ['view' => 'app/view/user/movie_details.php', 'css' => '/assets/css/movie_details.css'],

        //admin-sider

          // Admin-sider
          'admin_dashboard' => ['view' => 'app/view/admin/admin_dashboard.php', 'css' => '/assets/css/admin_styles.css'],
          'admin_movie' => ['view' => 'app/view/admin/admin_movie.php', 'css' => '/assets/css/admin_movie.css'],
          'manage_user' => ['view' => 'app/view/admin/admin_ManageUser.php', 'css' => '/assets/css/admin_styles.css'],
          'settings' => ['view' => 'app/view/admin/admin_settings.php', 'css' => '/assets/css/admin_styles.css'],

       //login og logout

       'login' => ['view' => 'app/view/auth/login.php', 'css' => '/assets/css/login.css'],
       'admin-login' => ['view' => 'app/view/auth/admin_login.php', 'css' => '/assets/css/admin_login.css'],
       'logout' => ['view' => 'app/view/auth/logout.php']

    

    ],
];
?>
