<?php
// Aktivér fejlrapportering
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inkludér init.php for at centralisere afhængigheder
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/init.php';

class Router {
    
    public static function route($page) {

            // Opret databaseforbindelse
       $db = Database::getInstance()->getConnection();
        // Start session via Security-klasse
        Security::startSession();
      

        // Definer kendte og beskyttede ruter
        $knownRoutes = [
            'homePage', 'about', 'program',
            'admin_dashboard', 'admin_movie', 'admin_settings', 'admin_ManageUsers',
            'book', 'review', 'login', 'logout'
        ];
        if (!in_array($page, $knownRoutes)) {
            header("HTTP/1.0 404 Not Found");
            echo "404 - Siden blev ikke fundet";
            return;
        }

        $protectedUserRoutes = ['book', 'review'];
        $protectedAdminRoutes = ['admin_dashboard', 'admin_movie', 'admin_settings', 'admin_ManageUsers'];

        // Valider, at ruten er kendt
        if (!in_array($page, $knownRoutes)) {
            header("HTTP/1.0 404 Not Found");
            echo "<h1>404 - Siden blev ikke fundet</h1>";
            echo "<p>Den side, du leder efter, eksisterer ikke. <a href='/Exam-1sem-bio/'>Gå tilbage til forsiden</a>.</p>";
            return;
        }

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
        // Routing-logik
        switch ($page) {
            // Public Pages
            case 'homePage':
            case 'about':
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

            case 'review':
                $pageController->showPage('review');
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

            // Default (404 fallback)
            default:
                header("HTTP/1.0 404 Not Found");
                echo "<h1>404 - Siden blev ikke fundet</h1>";
                echo "<p>Den side, du leder efter, eksisterer ikke. <a href='/Exam-1sem-bio/'>Gå tilbage til forsiden</a>.</p>";
                echo "Velkommen til $page.";
                break;
        }
    }
}
