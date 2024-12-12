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
                $this->$page(); // Kalder metoden med samme navn som siden
            } else {
                $this->pageLoader->loadUserPage($page); // Standard user page
            }
        } catch (Exception $e) {
            $this->pageLoader->renderErrorPage(500, "Fejl under indlæsning af siden: " . $e->getMessage());
        }
    }

    // Håndter forsiden
    public function homePage() {
        try {
            $movieFrontendModel = new MovieFrontendModel($this->db);
            $message = null;
    
            // Håndter kontaktformular
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
                // Hent og rens data fra kontaktformularen
                $name = htmlspecialchars(trim($_POST['name']));
                $email = htmlspecialchars(trim($_POST['email']));
                $subject = htmlspecialchars(trim($_POST['subject']));
                $userMessage = htmlspecialchars(trim($_POST['message']));
    
                // Valider data
                if (empty($name) || empty($email) || empty($subject) || empty($userMessage)) {
                    $message = "Alle felter skal udfyldes.";
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $message = "Ugyldig email-adresse.";
                } else {
                    // Opsætning af email
                    $to = "nsj@cruise-nights-cinema.dk"; // Din modtager-email
                    $headers = "From: nsj@cruise-nights-cinema.dk\r\n";
                    $headers .= "Reply-To: $email\r\n";
                    $body = "Navn: $name\nEmail: $email\n\nBesked:\n$userMessage";
    
                    // Send e-mail
                    if (mail($to, $subject, $body, $headers)) {
                        $message = "Tak for din besked, $name! Vi vender tilbage hurtigst muligt.";
                    } else {
                        $message = "Der opstod en fejl ved afsendelse af din besked. Prøv igen senere.";
                    }
                }
            }
    
            // Hent data til forsiden
            $data = [
                'upcomingMovies' => $movieFrontendModel->getUpcomingMovies(),
                'newsMovies' => $movieFrontendModel->getNewsMovies(),
                'dailyMovies' => $movieFrontendModel->getDailyShowings(),
                'settings' => $movieFrontendModel->getSiteSettings(),
                'contactMessage' => $message, // Feedback til kontaktformularen
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
}
