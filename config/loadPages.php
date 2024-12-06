<?php

// config/loadPages.php - Konfigurationsfil for sider
return [
    'default_css' => '/Exam-1sem-bio/assets/css/common.css',
    'pages' => [
        'homePage' => ['view' => '/app/view/user/homePage.php', 
        'css' => '/app/assets/css/homePage.css'],

        'program' => ['view' => '/app/view/user/program.php', 
        'css' => '/assets/css/program.css'],

        'movie' => ['view' => '/app/view/user/movie.php', 
        'css' => '/assets/css/movie.css'],

        'spots' => ['view' => '/app/view/user/spots.php', 
        'css' => '/assets/css/spots.css'],

        'tickets' => ['view' => '/app/view/user/tickets.php', 
        'css' => '/assets/css/tickets.css'],

        'about' => ['view' => '/app/view/user/about.php', 
        'css' => '/assets/css/about.css'],

        'movie_details' => ['view' => '/app/view/user/movie_details.php', 
        'css' => '/assets/css/movie_details.css'],

        //admin-sider

          // Admin-sider
          'admin_dashboard' => ['view' => '/app/view/admin/admin_dashboard.php', 
          'css' => '/assets/css/admin_styles.css'],

          'admin_daily_showings' => ['view' => '/app/view/admin/admin_daily_showings.php', 
          'css' => '/assets/css/admin_daily_showings.css'],

          'admin_movie' => ['view' => '/app/view/admin/admin_movie.php', 
          'css' => '/assets/css/admin_movie.css'],

          'admin_ManageUsers' => ['view' => '/app/view/admin/admin_ManageUsers.php', 
          'css' => '/assets/css/admin_ManageUsers'],

          'admin_settings' => ['view' => '/app/view/admin/admin_settings.php', 
          'css' => '/assets/css/admin_settings.css'],

          'admin_parking' => ['view' => '/app/view/admin/admin_parking.php',
          'css' => '/assets/css/admin_parking.css'],

         

       //login og logout

       'login' => ['view' => 'app/view/auth/login.php', 'css' => 'assets/css/login.css'],
       'admin-login' => ['view' => '/auth/admin_login.php', 'css' => 'assets/css/admin_login.css'],
       'logout' => ['view' => '/auth/logout.php'],

       '404' => [ 'view' => '/app/view/errors/404.php', 'css' => '/assets/css/errors.css'],
       '500' => ['view' => '/app/view/errors/500.php', 'css' => '/assets/css/errors.css'],
       

    ],
];

