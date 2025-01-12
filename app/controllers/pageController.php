<?php
require_once dirname(__DIR__, 2) . '/init.php';

class PageController {
    private $db;
    private $pageLoader;
    private $movieAdminController;
    private $adminController;
    //private $movieFrontendController;
    private $bookingModel;
    private $bookingController;
    private $moviedetailsController;
    private $authController;
  
    


    public function __construct() {
        // Initialiser database og afhængigheder
        $this->db = Database::getInstance()->getConnection();
        $this->pageLoader = new PageLoader($this->db);
        $this->movieAdminController = new MovieAdminController($this->db);
        $this->adminController = new AdminController(new AdminModel($this->db));
        //$this->movieFrontendController = new MovieFrontendController(new MovieFrontendModel($this->db));
        $this->bookingModel = new BookingModel($this->db);
        $this->bookingController = new BookingController($this->db);
        $this->moviedetailsController = new MovieDetailsController($this->db);
        $this->authController = new AuthController($this->db);
       
       
    }

   
    // Håndter en given side baseret på page-parametret
    public function showPage($page) {
        try {
            $slug = $_GET['slug'] ?? null; // Tjek om slug eksisterer
    
            if ($page === 'movie_details' && $slug) {
                $this->movie_details($slug);
            } else if (method_exists($this, $page)) {
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
            $contactController = new ContactController();
            $contactMessage = null;
    
            // Håndter kontaktformular
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
                // Brug ContactController til at håndtere mail-logik
                $contactMessage = $contactController->handleContactForm();
            }
    
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
        try {
            $slug = $_GET['slug'] ?? null;
    
            if (!$slug) {
                throw new Exception("Slug mangler i URL'en.");
            }
    
            $movieDetailsController = new MovieDetailsController($this->db);
            $movieDetailsController->showMovieDetails(htmlspecialchars($slug, ENT_QUOTES, 'UTF-8'));
        } catch (Exception $e) {
            error_log("Fejl i movie_details: " . $e->getMessage());
            $this->pageLoader->renderErrorPage(400, $e->getMessage());
        }
    }


public function handle_booking() {
    $this->bookingController->handleAction('handle_booking');
}

public function confirm_booking() {
    $this->bookingController->handleAction('confirm_booking');
}

public function cancel_booking() {
    $this->bookingController->handleAction('cancel_booking');
}


public function booking_success() {
    try {
        if (!isset($_GET['order_number'])) {
            throw new Exception("Ordrenummer ikke angivet.");
        }

        $orderNumber = $_GET['order_number'];
        $bookingModel = new BookingModel($this->db); // Instansier BookingModel
        $booking = $bookingModel->getBookingByOrderNumber($orderNumber, $_SESSION['user_id']);

        if (!$booking) {
            throw new Exception("Ingen bookingdata fundet for ordrenummeret.");
        }

        $this->pageLoader->renderPage('booking_success', ['booking' => $booking], 'user');
    } catch (Exception $e) {
        $this->pageLoader->renderErrorPage(500, "Fejl under indlæsning af kvitteringssiden: " . $e->getMessage());
    }
}





public function booking_receipt() {
    try {
        if (!isset($_GET['order_number'])) {
            throw new Exception("Ordrenummer mangler.");
        }

        $orderNumber = htmlspecialchars($_GET['order_number']);
        $userId = $_SESSION['user_id'];

        // Hent bookingdata via BookingController
        $booking = $this->bookingController->getBookingByOrderNumber($orderNumber, $userId);

        // Render kvitteringssiden med bookingdata
        $this->pageLoader->renderPage('booking_receipt', ['booking' => $booking], 'user');
    } catch (Exception $e) {
        $this->pageLoader->renderErrorPage(500, "Fejl under indlæsning af kvittering: " . $e->getMessage());
    }
}




public function admin_dashboard() {
    // Sikrer, at kun loggede admins kan få adgang
    $this->requireAdminLogin();

    try {
        // Initialiser AdminDashboardModel
        $adminDashboardModel = new AdminDashboardModel($this->db);

        // Hent nødvendige data til dashboardet
        $data = [
            'dailyShowings' => $adminDashboardModel->getDailyShowings(),
            'newsMovies' => $adminDashboardModel->getNewsMovies(),
        ];

        // Render admin dashboard med data
        $this->pageLoader->renderPage('admin_dashboard', $data, 'admin');
    } catch (Exception $e) {
        // Håndter fejl og vis en fejlside
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
        // Initialiser AdminBookingController
        $adminBookingController = new AdminBookingController($this->db);
        
        // Hent alle bookinger
        $bookings = $adminBookingController->listBookings();

        // Send data til viewet
        $this->pageLoader->renderPage('admin_bookings', ['bookings' => $bookings], 'admin');
    } catch (Exception $e) {
        $this->pageLoader->renderErrorPage(500, "Fejl under indlæsning af bookingsiden: " . $e->getMessage());
    }
}

public function admin_showings() {
    try {
        // Initialiser AdminShowingsController
        $adminShowingsController = new AdminShowingsController($this->db);

        // Kald controllerens main-metode til at håndtere handlingen
        $adminShowingsController->handleAction();
    } catch (Exception $e) {
        $this->pageLoader->renderErrorPage(500, "Fejl under håndtering af filmvisninger: " . $e->getMessage());
    }
}


    public function admin_ManageUsers() {
        try {
            // Behandl formularer og forespørgsler
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->adminController->handleCustomerAndEmployeeSubmission($_POST, $_GET);
            }

            // Hent data til visningen
            $data = $this->adminController->getCustomersAndEmployeesData();
            $this->pageLoader->renderPage('admin_ManageUsers', $data, 'admin');
        } catch (Exception $e) {
            $this->pageLoader->renderErrorPage(500, "Fejl under administration af brugere: " . $e->getMessage());
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

    public function profile() {
        $this->requireLogin(); // Beskyt profilen, så kun loggede brugere kan få adgang
    
        try {
            $userId = $_SESSION['user_id'];
    
            // Hent brugerdata fra databasen
            $authController = new AuthController($this->db);
            $user = $authController->getUserById($userId);
    
            // Hent brugerens bookinger
            $bookings = $this->bookingModel->getBookingsByUser($userId);
    
            // Del bookinger i aktuelle og tidligere
            $currentBookings = array_filter($bookings, function ($booking) {
                return new DateTime($booking['show_date']) >= new DateTime();
            });
            
            $pastBookings = array_filter($bookings, function ($booking) {
                return new DateTime($booking['show_date']) < new DateTime();
            });
    
            // Vis profil-siden
            $this->pageLoader->renderPage('profile', [
                'user' => $user,
                'currentBookings' => $currentBookings,
                'pastBookings' => $pastBookings,
            ], 'user');
        } catch (Exception $e) {
            $this->pageLoader->renderErrorPage(500, "Fejl under indlæsning af profil: " . $e->getMessage());
        }
    }
    
    
    
    
    public function register() {
    try {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            $authController = new AuthController($this->db);
            $authController->registerUser($name, $email, $password);

            // Omdiriger til login efter registrering
            header("Location: " . BASE_URL . "index.php?page=login");
            exit();
        } else {
            $this->pageLoader->renderPage('register', [], 'user');
        }
    } catch (Exception $e) {
        $this->pageLoader->renderPage('register', ['error' => $e->getMessage()], 'user');
    }
}

    
    public function login() {
    try {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            $authController = new AuthController($this->db);
            if ($authController->loginUser($email, $password)) {
                header("Location: " . BASE_URL . "index.php?page=homePage");
                exit();
            } else {
                $this->pageLoader->renderPage('login', ['error' => 'Forkert email eller adgangskode'], 'user');
            }
        } else {
            $this->pageLoader->renderPage('login', [], 'user');
        }
    } catch (Exception $e) {
        $this->pageLoader->renderErrorPage(500, $e->getMessage());
    }
}

    
    

    
    
    public function requireLogin() {
        if (!isset($_SESSION['user_id'])) {
            // Hvis brugeren ikke er logget ind, omdiriger dem til login-siden
            header("Location: index.php?page=login");
            exit;
        }
    }
    
    
    public function logout() {
        $this->authController->logoutUser(); // Kald logout-logik
        header("Location: " . BASE_URL . "index.php?page=homePage"); // Omdiriger
        exit();
    }
    
    
    public function requireAdminLogin() {
        if (!isset($_SESSION['admin_id'])) {
            header("Location: " . BASE_URL . "index.php?page=admin_login");
            exit();
        }
    }

    // Admin login-side
    public function admin_login() {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $email = trim($_POST['email']);
                $password = trim($_POST['password']);
    
                $authController = new AuthController($this->db);
    
                // Tjek admin-login
                if ($authController->loginAdmin($email, $password)) {
                    header("Location: " . BASE_URL . "index.php?page=admin_dashboard");
                    exit();
                } else {
                    $this->pageLoader->renderPage('admin_login', ['error' => 'Forkert email eller adgangskode'], 'admin');
                }
            } else {
                $this->pageLoader->renderPage('admin_login', [], 'admin');
            }
        } catch (Exception $e) {
            $this->pageLoader->renderErrorPage(500, "Fejl under admin-login: " . $e->getMessage());
        }
    }
    

        // Admin logout
        public function admin_logout() {
            $authController = new AuthController($this->db);
            $authController->logoutAdmin();
        }
    }
    

