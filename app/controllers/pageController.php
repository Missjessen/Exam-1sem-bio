<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/init.php';

class PageController {
    private $pageLoader;
    private $MovieAdminController;

    public function __construct() {
        // Hent databaseforbindelsen fra singletonen
        $db = Database::getInstance()->getConnection();
        
        // Initialiser nødvendige komponenter
        $this->pageLoader = new PageLoader($db);
       error_log("PageLoader blev initialiseret.");
     
        
        $this->MovieAdminController = new MovieAdminController($db);
        error_log("MovieAdminController blev initialiseret.");
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
    

    public function showAdminSettingsPage() {
        try {
            // Initialiser AdminController
            $AdminController = new AdminController(new AdminModel(Database::getInstance()->getConnection()));
            // Hent settings
            $settings = $AdminController->handleSettings();
            // Indlæs admin-siden med settings
            $this->pageLoader->loadAdminPage('admin_settings', compact('settings'));
        } catch (Exception $e) {
        
    }
    }
}
