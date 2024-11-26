<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/init.php';
class Router {
    public static function route($page) {
        // Hent den nyedatabaseforbindelsen
        $db = Database::getInstance()->getConnection();

        

        $pageController = new PageController($db);
        $MovieAdminController = new MovieAdminController($db);
        $pageLoader = new PageLoader($db);
        $adminController = new AdminController(new AdminModel($db));
      

        switch ($page) {
            case 'homePage':
            case 'about':
            case 'program':
                $pageController->showPage($page); // Brugerside
                break;

                case 'admin_movie':
                    $MovieAdminController = new MovieAdminController();
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        // Håndter POST-forespørgsler direkte via controller
                        $MovieAdminController->handlePostRequest();
                    } else {
                        // Håndter GET-forespørgsler ved at vise admin_movie-siden
                        $MovieAdminController->index();
                    }
                    break;

                case 'admin_settings':
                    $settings = $adminController->handleSettings();
                
                    // Send både settings og page til PageLoader
                    $pageLoader->loadAdminPage('admin_settings', [
                        'settings' => $settings,
                        'page' => 'admin_settings' // Marker, hvilken side der er aktiv
                    ]);
                    break;
                
            default:
                header("HTTP/1.0 404 Not Found");
                echo "Siden findes ikke.";
                break;
        }
    }
}

