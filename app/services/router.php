<?php
// Aktivér fejlrapportering
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inkludér init.php for at centralisere afhængigheder
require_once 'init.php';

class Router {
    
    public static function route($page) {

            // Opret databaseforbindelse
       $db = Database::getInstance()->getConnection();
        // Start session via Security-klasse
        Security::startSession();
      


        $protectedUserRoutes = ['book', 'review'];
        $protectedAdminRoutes = ['admin_dashboard', 'admin_movie', 'admin_settings', 'admin_ManageUsers'];

        // Check adgangsbeskyttelse
        if (in_array($page, $protectedAdminRoutes)) {
            Security::checkLogin(true); // Admin beskyttelse
        }

        if (in_array($page, $protectedUserRoutes)) {
            Security::checkLogin(); // Brugerbeskyttelse
        }

        // Opret nødvendige instanser
        $pageController = new PageController($db);
        $MovieAdminController = new MovieAdminController($db);
        $pageLoader = new PageLoader($db);
        $adminController = new AdminController(new AdminModel($db));
        $pageController = new PageController($pageLoader, $adminController);
       
        //$pageUserController = new PageUserController(new MovieFrontendModel($db));
            $movieFrontendController = new MovieFrontendController(new MovieFrontendModel($db));
            

        

        // Routing-logik
        switch ($page) {
            // Public Pages
            case 'homePage':
                $pageController->showHomePage();
                break;

            case 'movieDetails':
                if (isset($_GET['uuid'])) {
                    $movieFrontendController->showMovieDetails($_GET['uuid']);
                } else {
                    throw new Exception("Movie UUID missing.");
                }
                break;

            case 'program':
                $pageController->showPage($page);
                break;

            // Admin Pages
            case 'admin_dashboard':
                $pageLoader->loadAdminPage('admin_dashboard');
                break;

                case 'admin_movie':
                    
                    $MovieAdminController = new MovieAdminController($db);
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        // Håndter POST-forespørgsler direkte via controller
                        $MovieAdminController->handlePostRequest();
                    } else {
                        // Håndter GET-forespørgsler ved at vise admin_movie-siden
                        $MovieAdminController->index();
                    }
                    break;

                case 'admin_ManageUsers':
                    // Håndter POST- og GET-anmodninger
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' || !empty($_GET)) {
                        $pageController->handleCustomerAndEmployeeSubmission($_POST, $_GET);
                    }
                
                    // Hent data til visning
                    $data = $pageController->getCustomersAndEmployeesData();
                
                    // Indlæs siden
                    $pageLoader->loadAdminPage('admin_ManageUsers', $data);
                    break;

                    case 'admin_settings':
                        $settings = $adminController->handleSettings();
                    
                        // Send både settings og page til PageLoader
                        $pageLoader->loadAdminPage('admin_settings', [
                            'settings' => $settings,
                            'page' => 'admin_settings' // Marker, hvilken side der er aktiv
                        ]);
                        break;

            // User-specific pages
            case 'book':
                $pageController->showPage('book');
                break;
/* 
            case 'review':
                $pageController->showPage('review');
                break; */

                case 'register':
                    $pageController->showRegisterPage($_POST);
                    break;
                
                case 'login':
                    $pageController->showLoginPage($_POST);
                    break;
                
                case 'logout':
                    $pageController->handleLogout();
                    break;


                   case '404':
                    $errorController = new ErrorController();
                    $errorController->show404("Page not found: $page");
                    break;

                    default:
                    // Smid en undtagelse, hvis siden ikke findes
                    throw new Exception("Page not found: $page");
            }
            }
        
    }