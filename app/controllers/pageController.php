<?php
require_once dirname(__DIR__, 2) . '/init.php';

class PageController {
    private $db;
    private $pageLoader;
    private $movieAdminController;
    private $adminController;
    //private $movieFrontendController;
    //private $adminBookingModel;
    private $bookingController;
    private $moviedetailsController;
  
    


    public function __construct() {
        // Initialiser database og afhængigheder
        $this->db = Database::getInstance()->getConnection();
        $this->pageLoader = new PageLoader($this->db);
        $this->movieAdminController = new MovieAdminController($this->db);
        $this->adminController = new AdminController(new AdminModel($this->db));
        //$this->movieFrontendController = new MovieFrontendController(new MovieFrontendModel($this->db));
        //$this->adminBookingModel = new AdminBookingModel($this->db);
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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Håndter bookingdata fra formularen
            $bookingController->handleBooking($_POST);
        } else {
            $this->pageLoader->renderErrorPage(400, "Ugyldig anmodning til booking.");
        }
    } catch (Exception $e) {
        $this->pageLoader->renderErrorPage(500, "Fejl under håndtering af booking: " . $e->getMessage());
    }
}

public function confirm_booking() {
    try {
        $bookingController = new BookingController($this->db);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Bekræft booking og gem den i databasen
            $bookingController->confirmBooking($_POST);
        } else {
            $this->pageLoader->renderErrorPage(400, "Ugyldig anmodning til bekræftelse.");
        }
    } catch (Exception $e) {
        $this->pageLoader->renderErrorPage(500, "Fejl under bekræftelse af booking: " . $e->getMessage());
    }
}

public function cancel_booking() {
    try {
        $bookingController = new BookingController($this->db);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Annuller booking baseret på data
            $bookingController->cancelBooking($_POST);
        } else {
            $this->pageLoader->renderErrorPage(400, "Ugyldig anmodning til annullering.");
        }
    } catch (Exception $e) {
        $this->pageLoader->renderErrorPage(500, "Fejl under annullering af booking: " . $e->getMessage());
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

    public function admin_login() {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $email = trim($_POST['email']);
                $password = trim($_POST['password']);
    
                $authController = new AuthController($this->db);
    
                if ($authController->loginAdmin($email, $password)) {
                    // Brug BASE_URL til omdirigering
                    header("Location: " . BASE_URL . "/index.php?page=admin_dashboard");
                    exit;
                } else {
                    $data = ['error' => 'Forkert email eller adgangskode.'];
                    // Render siden med fejl
                    $this->pageLoader->renderPage('admin_login', $data, 'admin');
                }
            } else {
                // Hvis GET, vis login-siden
                $this->pageLoader->renderPage('admin_login', [], 'admin');
            }
        } catch (Exception $e) {
            $this->pageLoader->renderErrorPage(500, "Fejl under admin-login: " . $e->getMessage());
        }
    }
    
    
    public function login() {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $email = trim($_POST['email']);
                $password = trim($_POST['password']);
        
                $authController = new AuthController($this->db);
                if ($authController->loginUser($email, $password)) {
                    header("Location: index.php?page=profile");
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
    
                // Validering
                if (empty($name) || empty($email) || empty($password)) {
                    $data = ['error' => 'Alle felter skal udfyldes.'];
                    $this->pageLoader->renderPage('register', $data, 'auth');
                    return;
                }
    
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $data = ['error' => 'Indtast en gyldig email-adresse.'];
                    $this->pageLoader->renderPage('register', $data, 'auth');
                    return;
                }
    
                $authController = new AuthController($this->db);
                $authController->registerUser($name, $email, $password);
            } else {
                $this->pageLoader->renderPage('register', [], 'auth');
            }
        } catch (Exception $e) {
            $this->pageLoader->renderPage('register', ['error' => $e->getMessage()], 'auth');
        }
    }

}
