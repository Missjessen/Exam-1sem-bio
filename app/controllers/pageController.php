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
        if (!empty($_GET['slug'])) {
            $slug = htmlspecialchars($_GET['slug'], ENT_QUOTES, 'UTF-8');
            error_log("Slug fra URL: $slug"); // Debug
    
            $movieDetailsController = new MovieDetailsController($this->db);
            $movieDetailsController->showMovieDetails($slug);
        } else {
            throw new Exception("Slug mangler i URL'en.");
        }
    } catch (Exception $e) {
        error_log("Fejl i movie_details: " . $e->getMessage());
        $this->pageLoader->renderErrorPage(400, $e->getMessage());
    }
}


public function handle_booking() {
    try {
        $bookingController = new BookingController($this->db);
        $bookingController->handleAction('handle_booking');
    } catch (Exception $e) {
        $this->pageLoader->renderErrorPage(500, "Fejl under håndtering af booking: " . $e->getMessage());
    }
}

public function confirm_booking() {
    try {
        $bookingController = new BookingController($this->db);
        $bookingController->handleAction('confirm_booking');
    } catch (Exception $e) {
        $this->pageLoader->renderErrorPage(500, "Fejl under bekræftelse af booking: " . $e->getMessage());
    }
}

public function cancel_booking() {
    try {
        $bookingController = new BookingController($this->db);
        $bookingController->handleAction('cancel_booking');
    } catch (Exception $e) {
        $this->pageLoader->renderErrorPage(500, "Fejl under annullering af booking: " . $e->getMessage());
    }
}


public function bookingSummary() {
    try {
        // Sørg for, at session er startet
        if (!isset($_SESSION['pending_booking'])) {
            $this->pageLoader->renderErrorPage(400, "Ingen booking fundet. Start en ny booking.");
            return;
        }

        // Send bookingdata til viewet
        $this->pageLoader->renderPage('bookingSummary', $_SESSION['pending_booking'], 'user');
    } catch (Exception $e) {
        $this->pageLoader->renderErrorPage(500, "Fejl under indlæsning af booking oversigt: " . $e->getMessage());
    }
}




public function booking_Receipt() {
    try {
        $bookingController = new BookingController($this->db);
        $bookingController->showReceipt();
    } catch (Exception $e) {
        $this->pageLoader->renderErrorPage(500, "Fejl under indlæsning af kvitteringssiden: " . $e->getMessage());
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
        try {
            if (!isset($_SESSION['user_id'])) {
                header("Location: index.php?page=login");
                exit;
            }
    
            $userId = $_SESSION['user_id'];
            $bookings = $this->bookingModel->getBookingsByUser($userId);
    
            $this->pageLoader->renderPage('profile', ['bookings' => $bookings], 'user');
        } catch (Exception $e) {
            $this->pageLoader->renderErrorPage(500, "Fejl under indlæsning af profil: " . $e->getMessage());
        }
    }
    
    
    public function login() {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $email = trim($_POST['email']);
                $password = trim($_POST['password']);
    
                $authController = new AuthController($this->db);
                if ($authController->loginUser($email, $password)) {
                    // Tjek, om der er en redirect URL gemt i sessionen
                    $redirectUrl = $_SESSION['redirect_url'] ?? 'index.php?page=homePage';
                    unset($_SESSION['redirect_url']); // Fjern redirect URL efter login
                    header("Location: $redirectUrl");
                    exit;
                } else {
                    $this->pageLoader->renderPage('login', ['error' => 'Forkert email eller adgangskode.'], 'user');
                }
            } else {
                $this->pageLoader->renderErrorPage(400, "Ugyldig anmodning til login.");
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
    
                $authController = new AuthController($this->db);
                if ($authController->registerUser($name, $email, $password)) {
                    // Log brugeren ind efter registrering
                    $authController->loginUser($email, $password);
    
                    // Tjek redirect URL
                    $redirectUrl = $_SESSION['redirect_url'] ?? 'index.php?page=homePage';
                    unset($_SESSION['redirect_url']); // Fjern redirect URL efter login
                    header("Location: $redirectUrl");
                    exit;
                } else {
                    $this->pageLoader->renderPage('register', ['error' => 'Registreringen mislykkedes. Prøv igen.'], 'user');
                }
            } else {
                $this->pageLoader->renderErrorPage(400, "Ugyldig anmodning til registrering.");
            }
        } catch (Exception $e) {
            $this->pageLoader->renderErrorPage(500, "Fejl under registrering: " . $e->getMessage());
        }
    }
    
    
    
    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        header("Location: index.php?page=homePage");
        exit;
    }
    
}
