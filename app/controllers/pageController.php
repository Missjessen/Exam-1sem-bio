<?php
require_once BASE_PATH . 'init.php';

class PageController {
    private $db;
    private $pageLoader;
    private $MovieAdminController;
    private $adminController;
    private $userController;

    public function __construct() {
        // Initialiser databaseforbindelsen og komponenter
        $this->db = Database::getInstance()->getConnection();
        $this->pageLoader = new PageLoader($this->db);
        $this->MovieAdminController = new MovieAdminController($this->db);
        $this->adminController = new AdminController(new AdminModel($this->db));
        $this->userController = new UserController($this->db);
    }

    // Indlæser brugersider
    public function showPage($page) {
        $this->pageLoader->loadUserPage($page);
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
            $this->pageLoader->loadAdminPage('admin_movie', [
                'movies' => $movies,
                'actors' => $actors,
                'genres' => $genres,
                'movieToEdit' => $movieToEdit,
            ]);
        } catch (Exception $e) {
            error_log("Fejl i showAdminMoviePage: " . $e->getMessage());
            $this->pageLoader->loadErrorPage("Noget gik galt under indlæsningen af filmadministrationen.");
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
            $this->pageLoader->loadAdminPage('admin_settings', compact('settings'));
       } catch (Exception $e) {
            error_log("Fejl i showAdminSettingsPage: " . $e->getMessage());
            $this->pageLoader->loadErrorPage("Noget gik galt under indlæsningen af indstillinger.");
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