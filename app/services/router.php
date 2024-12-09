<?php

require_once dirname(__DIR__, 2) . '/init.php';

class Router {
    
    public static function route($page) {
        // Opret databaseforbindelse
        $db = Database::getInstance()->getConnection();

        // Opret nødvendige instanser
        $pageController = new PageController($db); // Variabelnavn er allerede korrekt
        $pageLoader = new PageLoader($db); // Variabelnavn er allerede korrekt
        $adminController = new AdminController(new AdminModel($db)); // Variabelnavn er allerede korrekt
        $movieFrontendController = new MovieFrontendController(new MovieFrontendModel($db)); // Variabelnavn er allerede korrekt
        $showingsController = new AdminShowingsController($db); // Variabelnavn er allerede korrekt
        $movieDetailsController = new MovieDetailsController($db); // Variabelnavn er allerede korrekt
       $dashboardController = new AdminDashboardController($db); // Variabelnavn er allerede korrekt
      

        // Routing-logik
        switch ($page) {
            // Public Pages
            case 'homePage':
                $pageController->showHomePage();
                break;

            case 'movie_details':
                if (!empty($_GET['slug'])) {
                    $movieDetailsController = new MovieDetailsController($db); // Ingen ændringer
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
                $dashboardController = new AdminDashboardController($db); // Ingen ændringer
                $dashboardController->showDashboard();
                break;

            case 'admin_daily_showings':
                $action = $_GET['action'] ?? 'list';
                $controller = new AdminShowingsController($db); // Ingen ændringer
                
                if ($action === 'add' || $action === 'edit') {
                    $controller->handleRequest($action);
                    $data = $controller->index(); // Genindlæs data
                } else {
                    $data = $controller->handleRequest($action);
                }
                
                $pageLoader->loadAdminPage('admin_daily_showings', $data);
                break;

            case 'admin_movie':
                $movieAdminController = new MovieAdminController($db); // **Rettet fra `$MovieAdminController` til `$movieAdminController`**
                
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Håndter POST-forespørgsler direkte via controller
                    $movieAdminController->handlePostRequest();
                } else {
                    // Håndter GET-forespørgsler ved at vise admin_movie-siden
                    $movieAdminController->index();
                }
                break;

            case 'admin_ManageUsers':
                if ($_SERVER['REQUEST_METHOD'] === 'POST' || !empty($_GET)) {
                    $pageController->handleCustomerAndEmployeeSubmission($_POST, $_GET);
                }
                
                $data = $pageController->getCustomersAndEmployeesData();
                $pageLoader->loadAdminPage('admin_ManageUsers', $data);
                break;

            case 'admin_settings':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $adminController->handleSettings($_POST);
                    header("Location: ?page=admin_settings");
                    exit;
                } else {
                    $settings = $adminController->handleSettings();
                    $pageLoader->loadAdminPage('admin_settings', [
                        'settings' => $settings,
                        'page' => 'admin_settings',
                    ]);
                }
                break;

            // User-specific pages
            case 'admin_bookings':
                $pageController->showAdminBookingsPage();
                break;

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
                $errorController = new ErrorController(); // Ingen ændringer
                $errorController->show404("Page not found: $page");
                break;

                default:
                // Ukendt side: Send til 404
                (new BaseController())->handleError("Page not found: $page", 404);
        }
    } catch (Exception $e) {
        // Fejl i routing: Send til 500
        (new BaseController())->handleError("Routing error: " . $e->getMessage(), 500);
    }
}

