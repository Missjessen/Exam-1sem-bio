<?php
require_once dirname(__DIR__, 2) . '/init.php';

class PageController {
    private $db;
    private $PageLoader;
    private $MovieAdminController;
    private $adminController;
    private $adminBookingModel;
   
   
  

    public function __construct() {
        // Initialiser databaseforbindelsen og komponenter
        $this->db = Database::getInstance()->getConnection();
        $this->PageLoader = new PageLoader($this->db);
        $this->MovieAdminController = new MovieAdminController($this->db);
        $this->adminController = new AdminController(new AdminModel($this->db));
        $this->adminBookingModel = new AdminBookingModel($this->db);
    

        
    }

   // Indlæser en side
public function showPage($page) {
    try {
        if ($page === 'admin_daily_showings') {
            // Håndter admin_daily_showings separat
            $this->handleAdminDailyShowings();
        } else {
            // Standard håndtering for brugersider
            $this->PageLoader->loadUserPage($page);
        }
    } catch (Exception $e) {
        $this->handleError("Fejl under indlæsning af siden: " . $e->getMessage());
    }
}
private function handleAdminDailyShowings() {
    $action = $_GET['action'] ?? 'list';

    if (!in_array($action, ['list', 'add', 'edit', 'delete'])) {
        // Hvis en ukendt action er anmodet, log fejl eller send bruger en 404
        echo "Ugyldig handling: " . htmlspecialchars($action);
        exit;
    }

    $showingsController = new AdminShowingsController($this->db);

    // Debugging: Tjek hvad controlleren returnerer
    $data = $showingsController->handleRequest($action);
     // Udskriv data for at sikre, at vi får noget tilbage
    exit;  // Stop for at se output

    $this->PageLoader->loadAdminPage('admin_daily_showings', $data);
}

  // Ny metode til håndtering af movie_details
  public function showMovieDetailsPage($slug) {
    try {
        $movieDetailsModel = new MovieDetailsModel($this->db);
        $movie = $movieDetailsModel->getMovieDetailsBySlug($slug);

        if (!$movie) {
            throw new Exception("Filmen blev ikke fundet.");
        }

        $showtimes = $movieDetailsModel->getShowtimesForMovie($movie['id']);

        // Indlæs view med data
        $this->PageLoader->loadUserPage('movie_details', [
            'movie' => $movie,
            'showtimes' => $showtimes,
        ]);
    } catch (Exception $e) {
        $this->handleError("Fejl under visning af filmdetaljer: " . $e->getMessage());
    }
}




    // Indlæser forsiden
    public function showHomePage() {
        try {
            $movieFrontendModel = new MovieFrontendModel($this->db);
            $this->PageLoader->showHomePage($movieFrontendModel);
        } catch (Exception $e) {
            $this->handleError("Fejl under indlæsning af forsiden: " . $e->getMessage());
        }
    }


    public function showProgramPage() {
        try {
            $movieAdminModel = new MovieAdminModel($this->db); // Brug eksisterende model
            $movies = $movieAdminModel->getAllMovies(); // Hent alle film
    
            $this->PageLoader->loadUserPage('program', [
                'movies' => $movies, // Send filmdata til view
            ]);
        } catch (Exception $e) {
            $this->handleError("Fejl under indlæsning af programsiden: " . $e->getMessage());
        }
    }
    
    

    public function showAdminMoviePage() {
        try {
            // Hent alle film, genrer og skuespillere
            $movies = $this->MovieAdminController->getAllMoviesWithDetails();
            $actors = $this->MovieAdminController->getAllActors();
            $genres = $this->MovieAdminController->getAllGenres();
    
            // Forbered data til redigering af film
            $movieToEdit = null;
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $action = $_POST['action'] ?? null;
    
                if ($action === 'edit') {
                    $movieId = $_POST['movie_id'] ?? null;
                    if ($movieId) {
                        $movieToEdit = $this->MovieAdminController->getMovieDetails($movieId);
                    }
                }
            }
    
            // Kontroller, om alle nødvendige data er hentet
            if (!$movies || !$actors || !$genres) {
                throw new Exception("Data for film, genrer eller skuespillere kunne ikke hentes.");
            }
    
            // Indlæs admin_movie view med alle data
            $this->PageLoader->loadAdminPage('admin_movie', [
                'movies' => $movies,
                'actors' => $actors,
                'genres' => $genres,
                'movieToEdit' => $movieToEdit,
            ]);
        } catch (Exception $e) {
            error_log("Fejl i showAdminMoviePage: " . $e->getMessage());
            $this->PageLoader->loadErrorPage("Noget gik galt under indlæsningen af filmadministrationen.");
        }
    }

    public function handleCustomersAndEmployeesPage() {
        $this->adminController->handleCustomerAndEmployeeSubmission($_POST, $_GET);

        return [
            'customers' => $this->adminController->getAllCustomers(),
            'employees' => $this->adminController->getAllEmployees(),
            'editCustomer' => isset($_GET['edit_customer_id']) ? $this->adminController->getCustomerById($_GET['edit_customer_id']) : null,
            'editEmployee' => isset($_GET['edit_employee_id']) ? $this->adminController->getEmployeeById($_GET['edit_employee_id']) : null,
        ];
    }

    public function showAdminSettingsPage() {
        try {
            // Initialiser AdminController
            $AdminController = new AdminController(new AdminModel(Database::getInstance()->getConnection()));
            // Hent settings
            $settings = $AdminController->handleSettings();
            // Indlæs admin-siden med settings
            $this->PageLoader->loadAdminPage('admin_settings', compact('settings'));
       } catch (Exception $e) {
            error_log("Fejl i showAdminSettingsPage: " . $e->getMessage());
            $this->PageLoader->loadErrorPage("Noget gik galt under indlæsningen af indstillinger.");
        }
    }

    public function handleCustomerAndEmployeeSubmission($postData, $getData) {
        $this->adminController->handleCustomerAndEmployeeSubmission($postData, $getData);
    }

    public function getCustomersAndEmployeesData() {
        return $this->adminController->getCustomersAndEmployeesData();
    }

    public function showRegisterPage($postData = null) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $message = $this->userController->registerUser($postData);
                echo $message; // Succesbesked
            } catch (Exception $e) {
                echo "Fejl: " . $e->getMessage(); // Fejlhåndtering
            }
        } else {
            require_once __DIR__ . '/../auth/register_form.php';
        }
    }


    public function handleSettings() {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Videregiv POST-data til AdminController
                $this->adminController->handleSettings($_POST);
    
                // Rediriger for at undgå gentagne POST-forespørgsler
                header("Location: ?page=admin_settings");
                exit;
            }
    
            // Hent eksisterende indstillinger
            $settings = $this->adminController->handleSettings();
    
            // Indlæs settings-siden
            $this->PageLoader->loadAdminPage('admin_settings', compact('settings'));
        } catch (Exception $e) {
            error_log("Fejl i handleSettings: " . $e->getMessage());
            $this->PageLoader->loadErrorPage("Noget gik galt under håndtering af indstillinger.");
        }
    }

    public function showAdminBookingsPage() {
        try {
            require_once __DIR__ . '/../models/AdminBookingModel.php';
            $bookingModel = new AdminBookingModel($this->db);
    
            // Hent alle bookinger
            $bookings = $bookingModel->getAllBookings();
    
            // Send data til view via PageLoader
            $this->PageLoader->loadAdminPage('admin_bookings', ['bookings' => $bookings]);
        } catch (Exception $e) {
            $this->handleError("Fejl under indlæsning af bookinger: " . $e->getMessage());
        }
    }
    
    

    public function showLoginPage($postData = null) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $message = $this->userController->loginUser($postData);
                echo $message; // Velkomstbesked
            } catch (Exception $e) {
                echo "Fejl: " . $e->getMessage(); // Fejlhåndtering
            }
        } else {
            require_once __DIR__ . '/../auth/login_form.php';
        }
    }

    public function handleLogout() {
        Security::logout();
    }

    
}