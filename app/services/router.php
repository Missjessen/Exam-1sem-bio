<?php
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    echo "<pre><strong>Fejl:</strong> [$errno] $errstr - $errfile:$errline</pre>";
    return false; // Sørg for, at standard PHP-fejl også håndteres
});

set_exception_handler(function ($exception) {
    echo "<pre><strong>Exception:</strong> " . $exception->getMessage() . "</pre>";
    echo "<pre>" . $exception->getTraceAsString() . "</pre>";
});

// Resten af din routerkode

require_once dirname(__DIR__, 2) . '/init.php';

class Router {
    
    public static function route($page) {
    
            // Opret databaseforbindelse
       $db = Database::getInstance()->getConnection();
        // Start session via Security-klasse
       

        // Opret nødvendige instanser
        $pageController = new PageController($db);
        $MovieAdminController = new MovieAdminController($db);
        $pageLoader = new PageLoader($db);
        $adminController = new AdminController(new AdminModel($db));
        $pageController = new PageController($pageLoader);
       
        //$pageUserController = new PageUserController(new MovieFrontendModel($db));
            $movieFrontendController = new MovieFrontendController(new MovieFrontendModel($db));
            $showingsController = new AdminShowingsController($db);
            $movieDetailsController = new MovieDetailsController($db);

            

        

        // Routing-logik
        switch ($page) {
            // Public Pages
            case 'homePage':
                $pageController->showHomePage();
                break;

              /*   case 'sendMail':
                    $emailController = new \App\Controllers\EmailController();
                    $emailController->sendContactForm();
                    break; */

                    case 'movie_details':
                        if (!empty($_GET['slug'])) {
                            $movieDetailsController = new MovieDetailsController($db);
                            $movieDetailsController->showMovieDetailsBySlug($_GET['slug']);
                        } else {
                            throw new Exception("Slug mangler i URL'en.");
                        }
                        break;

                        case 'program':
                            $pageController->showProgramPage();
                            break;
            // Admin Pages
            case 'admin_dashboard':
                $pageLoader->loadAdminPage('admin_dashboard');
                break;

                case 'admin_daily_showings':
                   
                    $showingsController = new AdminShowingsController($db);
                    $action = $_GET['action'] ?? 'list';
                   
                    $data = $showingsController->handleRequest($action);
                 
                    $pageLoader->loadAdminPage('admin_daily_showings', $data);
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
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            // Håndter opdatering af indstillinger via POST
                            $adminController->handleSettings($_POST);
                    
                            // Rediriger for at undgå gentagne POST-forespørgsler
                            header("Location: ?page=admin_settings");
                            exit;
                        } else {
                            // Håndter GET for at hente eksisterende indstillinger
                            $settings = $adminController->handleSettings();
                    
                            // Send settings og page til PageLoader
                            $pageLoader->loadAdminPage('admin_settings', [
                                'settings' => $settings,
                                'page' => 'admin_settings', // Marker, hvilken side der er aktiv
                            ]);
                        }
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
    echo "<pre>Ukendt side: $page</pre>";
    throw new Exception("Page not found: $page");
    break;
            }
        
    }
}