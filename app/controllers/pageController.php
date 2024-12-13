<?php
require_once dirname(__DIR__, 2) . '/init.php';

class PageController {
    private $db;
    private $pageLoader;
    private $movieAdminController;
    private $adminController;
    private $movieFrontendController;
    private $adminBookingModel;
    private $bookingController;
  
    


    public function __construct() {
        // Initialiser database og afhængigheder
        $this->db = Database::getInstance()->getConnection();
        $this->pageLoader = new PageLoader($this->db);
        $this->movieAdminController = new MovieAdminController($this->db);
        $this->adminController = new AdminController(new AdminModel($this->db));
        $this->movieFrontendController = new MovieFrontendController(new MovieFrontendModel($this->db));
        $this->adminBookingModel = new AdminBookingModel($this->db);
        $this->bookingController = new BookingController($this->db);
       
    }

    // Håndter en given side baseret på page-parametret
    public function showPage($page) {
        try {
            if (method_exists($this, $page)) {
                $this->$page(); // Kald den relevante metode
            } else {
                $this->pageLoader->loadUserPage($page); // Standard user page
            }
        } catch (Exception $e) {
            $this->pageLoader->renderErrorPage(500, "Fejl under indlæsning af siden: " . $e->getMessage());
        }
    }

    

    public function homePage() {
        try {
            $movieFrontendModel = new MovieFrontendModel($this->db);
    
            // Hent besked fra session, hvis den findes
            session_start();
            $contactMessage = $_SESSION['contactMessage'] ?? null;
            unset($_SESSION['contactMessage']); // Ryd besked, så den kun vises én gang
    
            // Hent data til forsiden
            $data = [
                'upcomingMovies' => $movieFrontendModel->getUpcomingMovies(),
                'newsMovies' => $movieFrontendModel->getNewsMovies(),
                'dailyMovies' => $movieFrontendModel->getDailyShowings(),
                'settings' => $movieFrontendModel->getSiteSettings(),
                'contactMessage' => $contactMessage,
            ];
    
            // Render forsiden
            $this->pageLoader->renderPage('homePage', $data, 'user');
        } catch (Exception $e) {
            $this->pageLoader->renderErrorPage(500, "Fejl under indlæsning af forsiden: " . $e->getMessage());
        }
    }
    

    // Håndter program
    public function program() {
        try {
            $movies = $this->movieAdminController->getAllMoviesWithDetails();
            $this->pageLoader->renderPage('program', ['movies' => $movies], 'user');
        } catch (Exception $e) {
            $this->pageLoader->renderErrorPage(500, "Fejl under indlæsning af programsiden: " . $e->getMessage());
        }
    }

    // Håndter filmdetaljer
    public function movie_details() {
        if (!empty($_GET['slug'])) {
            try {
                $this->movieFrontendController->showMovieDetails($_GET['slug']);
            } catch (Exception $e) {
                $this->pageLoader->renderErrorPage(404, "Filmen blev ikke fundet.");
            }
        } else {
            $this->pageLoader->renderErrorPage(400, "Slug mangler i URL'en.");
        }
    }

    public function booking() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->bookingController->handleBooking($_POST); // Behandl booking POST-anmodning
            } catch (Exception $e) {
                $this->pageLoader->renderErrorPage(500, "Fejl under booking: " . $e->getMessage());
            }
        } else {
            $this->pageLoader->renderErrorPage(400, "Ugyldig anmodning til booking.");
        }
    }

   public function bookingAndReceipt() {
    try {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Håndter booking POST-anmodning
            $this->bookingController->handleBooking($_POST);
        } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Håndter kvittering GET-anmodning
            $bookingId = $_GET['booking_id'] ?? null;

            if (!$bookingId) {
                throw new Exception("Booking ID mangler i URL'en.");
            }

            $receiptData = $this->bookingController->getBookingDetails($bookingId);
            
            if (!$receiptData) {
                throw new Exception("Booking med ID '$bookingId' blev ikke fundet.");
            }

            // Render kvitteringssiden
            $this->pageLoader->renderPage('receipt', ['receiptData' => $receiptData], 'user');
        } else {
            // Hvis anmodningen hverken er POST eller GET
            throw new Exception("Ugyldig anmodningstype.");
        }
    } catch (Exception $e) {
        $this->pageLoader->renderErrorPage(500, "Fejl: " . $e->getMessage());
    }
}
    

    // Admin dashboard
    public function admin_dashboard() {
        try {
            // Sikkerhedstjek for admin-login
            if (!isset($_SESSION['admin_id'])) {
                // Hvis admin ikke er logget ind, omdiriger til admin_login
                header("Location: index.php?page=admin_login");
                exit;
            }
    
            // Hent data til dashboard
            $adminDashboardModel = new AdminDashboardModel($this->db);
            $data = [
                'dailyShowings' => $adminDashboardModel->getDailyShowings(),
                'newsMovies' => $adminDashboardModel->getNewsMovies(),
            ];
    
            // Render admin-dashboard
            $this->pageLoader->renderPage('admin_dashboard', $data, 'admin');
        } catch (Exception $e) {
            // Fejlhåndtering
            $this->pageLoader->renderErrorPage(500, "Fejl under indlæsning af admin dashboard: " . $e->getMessage());
        }
    }

    // Admin movie
    public function admin_movie() {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->movieAdminController->handlePostRequest();
            } else {
                $this->movieAdminController->index();
            }
        } catch (Exception $e) {
            $this->pageLoader->renderErrorPage(500, "Fejl under indlæsning af filmadministration: " . $e->getMessage());
        }
    }

    // Bookinger
    public function admin_dashboard() {
        try {
            $data = [
                'dailyShowings' => (new AdminDashboardModel($this->db))->getDailyShowings(),
                'newsMovies' => (new AdminDashboardModel($this->db))->getNewsMovies(),
            ];
            $this->pageLoader->loadAdminPage('admin_dashboard', $data);
        } catch (Exception $e) {
            $this->pageLoader->renderErrorPage(500, "Fejl under indlæsning af admin dashboard: " . $e->getMessage());
        }
    }
    

    // Indstillinger
    public function admin_settings() {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->adminController->handleSettings($_POST);
                header("Location: ?page=admin_settings");
                exit;
            } else {
                $settings = $this->adminController->handleSettings();
                $this->pageLoader->renderPage('admin_settings', ['settings' => $settings], 'admin');
            }
        } catch (Exception $e) {
            $this->pageLoader->renderErrorPage(500, "Fejl under indlæsning af indstillinger: " . $e->getMessage());
        }
    }

    // Fejlhåndtering
    public function handleError($message) {
        error_log($message);
        $this->pageLoader->renderErrorPage(500, $message);
    }


    public function admin_login() {
        try {
            // Hvis allerede logget ind som admin, omdiriger til dashboard
            if (isset($_SESSION['admin_id'])) {
                header("Location: index.php?page=admin_dashboard");
                exit;
            }
    
            // Behandling af login-formular
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $email = trim($_POST['email']);
                $password = trim($_POST['password']);
    
                $authController = new AuthController($this->db);
                if ($authController->adminLogin($email, $password)) {
                    header("Location: index.php?page=admin_dashboard");
                    exit;
                } else {
                    $data = ['error' => 'Forkert email eller adgangskode.'];
                    $this->pageLoader->renderPage('admin_login', $data, 'admin');
                }
            } else {
                // Vis login-siden
                $this->pageLoader->renderPage('admin_login', [], 'admin');
            }
        } catch (Exception $e) {
            $this->pageLoader->renderErrorPage(500, "Fejl under admin-login: " . $e->getMessage());
        }
    }
    public function profile() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?page=login");
            exit;
        }

        // Data til profil
        $data = ['userName' => $_SESSION['username']];
        $this->pageLoader->renderPage('profile', $data, 'user');
    }

    public function login() {
        $this->pageLoader->renderPage('login', [], 'user');
    }

    public function register() {
        $this->pageLoader->renderPage('register', [], 'user');
    }


    private function ensureLoggedIn() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            header("Location: " . BASE_URL . "index.php?page=login");
            exit;
        }
    }
}
