<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/init.php'; // Inkluder init.php med $db og autoloader

class Router {
    public static function route($page) {

        
        // Opret forbindelse til databasen og opret PageLoader med $db
        global $db; // Brug den globale $db-forbindelse, der er initialiseret i connection.php
        if (!isset($db)) {
            $logFilePath = $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/logs/errors.log';
            error_log("Fejl: Databaseforbindelsen kunne ikke oprettes.\n", 3, $logFilePath);
            die("Databaseforbindelse fejlede.");
        }

        $pageLoader = new PageLoader($db); // Opret PageLoader med $db
        $logFilePath = $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/logs/errors.log';

        $userModel = new UserModel($db);
        $userController = new UserController($userModel);

        $adminModel = new AdminModel($db);
        $adminController = new AdminController($adminModel);

        // Routing baseret på side-type
        switch ($page) {
            // User Pages
            case 'homePage':
            case 'program':
            case 'about':
                error_log("Laster side: $page (bruger-side)\n", 3, $logFilePath);
                $pageLoader->loadPage($page); // Indlæs hele siden via PageLoader
                break;

            case 'movie':
            case 'movieDetails':
                // Hent slug fra URL'en og validér den
                $slug = isset($_GET['slug']) ? htmlspecialchars($_GET['slug'], ENT_QUOTES, 'UTF-8') : null;

                if ($slug) {
                    // Log hvilken side og parametre, der bliver hentet
                    error_log("Laster side: movie med slug $slug (bruger-side)\n", 3, $logFilePath);

                    // Brug controlleren til at vise filmen baseret på slug
                    $userController->showMovie($slug); // Ændret fra movieDetails() til showMovie()
                } else {
                    // Hvis slug ikke findes eller er ugyldigt, log fejlen og vis en 404 fejl
                    error_log("Fejl: Manglende eller ugyldigt slug ved forsøg på at laste movie-side\n", 3, $logFilePath);
                    header("HTTP/1.0 404 Not Found");
                    echo "Film ikke fundet";
                    exit;
                }
                break;

            // Admin Pages
            case 'admin_dashboard':
            case 'admin_movie':    
            case 'manage_pages':
            case 'admin_ManageUsers':
            case 'admin_settings':
                error_log("Laster side: $page (admin-dashboard)\n", 3, $logFilePath);
                $pageLoader->loadPage($page); // Indlæs hele adminsiden via PageLoader
                break;

            // Admin Movie Management - specifik funktionalitet
            case 'add_movie':
                error_log("Laster side: add_movie (admin-dashboard)\n", 3, $logFilePath);
                $adminController->addMovie();
                break;

            case 'edit_movie':
                $movieId = isset($_GET['id']) ? htmlspecialchars($_GET['id'], ENT_QUOTES, 'UTF-8') : null;
                if ($movieId) {
                    error_log("Laster side: edit_movie med ID: $movieId (admin-dashboard)\n", 3, $logFilePath);
                    $adminController->editMovie($movieId);
                } else {
                    error_log("Fejl: Manglende eller ugyldigt ID ved forsøg på at laste edit_movie\n", 3, $logFilePath);
                    header("HTTP/1.0 404 Not Found");
                    echo "Film ikke fundet";
                    exit;
                }
                break;

            // Default handling - vis startsiden, hvis siden ikke findes
            default:
                error_log("Laster standardside: home\n", 3, $logFilePath);
                $pageLoader->loadPage('home');
                break;
        }
    }
}
