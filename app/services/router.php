<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/init.php';
class Router {
    public static function route($page) {
        global $db;
        if (!isset($db)) {
            die("Databaseforbindelse fejlede.");
        }

        $pageController = new PageController($db);

        switch ($page) {
            case 'homePage':
            case 'program':
            case 'about':
            case 'movie':
            case 'movieDetails':
                $pageController->showPage($page);
                break;
            
            case 'add_movie':
                $pageController->addMovie();
                break;

            case 'edit_movie':
                $movieId = $_GET['id'] ?? null;
                $pageController->editMovie($movieId);
                break;

                case 'admin_dashboard':
                case 'admin_movie':
                case 'admin_MangeUsers':
                case 'admin_settings':
                        //$pageController->showAdminPage($page);
                        break;

            case '/Exam-1sem-bio/upload':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $controller = new UploadController();
                    $controller->handleFileUpload($_FILES['poster'], $_POST['desc']);
                    }
                     break;
                        

                default:
                        // Hvis siden ikke findes, kan du omdirigere til en 404-side
                        header("HTTP/1.0 404 Not Found");
                        echo "Siden blev ikke fundet.";
                        break;
                }
        }
    }

