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
            $adminDashboardModel = new AdminDashboardModel($this->db);
            $data = [
                'dailyShowings' => $adminDashboardModel->getDailyShowings(),
                'newsMovies' => $adminDashboardModel->getNewsMovies(),
            ];
            $this->pageLoader->renderPage('admin_dashboard', $data, 'admin');
        } catch (Exception $e) {
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
    public function admin_bookings() {
        try {
            $data = $this->adminBookingModel->getAllBookings();
            $this->pageLoader->renderPage('admin_bookings', $data, 'admin');
        } catch (Exception $e) {
            $this->pageLoader->renderErrorPage(500, "Fejl under indlæsning af bookingsiden: " . $e->getMessage());
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

    public function showLoginPage($data = []) {
        $viewPath = __DIR__ . '/../auth/login_form.php'; // Stien til login_form.php
        
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            throw new Exception("Filen login_form.php kunne ikke findes.");
        }
    }

   public function login() {
    try {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            $authController = new AuthController(new CustomerModel($this->db));
            if ($authController->login($email, $password)) {
                $redirect = $_SESSION['redirect_after_login'] ?? BASE_URL . 'index.php?page=profile';
                unset($_SESSION['redirect_after_login']);
                header("Location: " . $redirect);
                exit;
            } else {
                $data = ['error' => 'Forkert email eller adgangskode.'];
                $this->pageLoader->renderPage('login', $data, 'user');
            }
        } else {
            $this->pageLoader->renderPage('login', [], 'user');
        }
    } catch (Exception $e) {
        $this->pageLoader->renderErrorPage(500, "Fejl under login: " . $e->getMessage());
    }
}

    
    public function register() {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $name = trim($_POST['name']);
                $email = trim($_POST['email']);
                $password = trim($_POST['password']);
                $confirmPassword = trim($_POST['confirm_password']);
    
                if ($password !== $confirmPassword) {
                    $data = ['error' => 'Adgangskoderne matcher ikke.'];
                    $this->pageLoader->renderPage('register', $data, 'user');
                    return;
                }
    
                $authController = new AuthController(new CustomerModel($this->db));
                if ($authController->register($name, $email, $password)) {
                    $_SESSION['message'] = "Registrering fuldført! Du kan nu logge ind.";
                    header("Location: " . BASE_URL . "index.php?page=login");
                    exit;
                } else {
                    $data = ['error' => 'Registrering mislykkedes. Prøv igen.'];
                    $this->pageLoader->renderPage('register', $data, 'user');
                }
            } else {
                $this->pageLoader->renderPage('register', [], 'user');
            }
        } catch (Exception $e) {
            $this->pageLoader->renderErrorPage(500, "Fejl under registrering: " . $e->getMessage());
        }
    }
    
    public function profile() {
        try {
            if (!isset($_SESSION['user_id'])) {
                $_SESSION['redirect_after_login'] = BASE_URL . "index.php?page=profile";
                header("Location: " . BASE_URL . "index.php?page=login");
                exit;
            }
    
            $customerId = $_SESSION['user_id'];
            $bookingController = new BookingController(new BookingModel($this->db));
            $customerBookings = $bookingController->getCustomerBookings($customerId);
    
            $data = ['customerBookings' => $customerBookings];
            $this->pageLoader->renderPage('profile', $data, 'user');
        } catch (Exception $e) {
            $this->pageLoader->renderErrorPage(500, "Fejl under indlæsning af profilen: " . $e->getMessage());
        }
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
