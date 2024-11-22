<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/init.php';
class Router {
    public static function route($page) {
        global $db;

        $pageController = new PageController($db);
        $movieAdminController = new MovieAdminController($db);

        switch ($page) {
            case 'homePage':
            case 'about':
            case 'program':
                $pageController->showPage($page); // Brugerside
                break;

            case 'admin_movie':
                $pageController->showAdminMoviePage(); // Admin-side for film
             
                
               
                break;

            default:
                header("HTTP/1.0 404 Not Found");
                echo "Siden findes ikke.";
                break;
        }
    }
}

