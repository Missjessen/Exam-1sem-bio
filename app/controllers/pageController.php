<?php
require_once dirname(__DIR__, 2) . '/init.php';

class PageController {
    private $db;
    private $pageLoader;
    private $movieAdminController;
    private $adminController;
    private $movieFrontendController;
    private $adminBookingModel;

    public function __construct() {
        // Initialiser database og afhængigheder
        $this->db = Database::getInstance()->getConnection();
        $this->pageLoader = new PageLoader($this->db);
        $this->movieAdminController = new MovieAdminController($this->db);
        $this->adminController = new AdminController(new AdminModel($this->db));
        $this->movieFrontendController = new MovieFrontendController(new MovieFrontendModel($this->db));
        $this->adminBookingModel = new AdminBookingModel($this->db);
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
            $data = [
                'upcomingMovies' => $movieFrontendModel->getUpcomingMovies(),
                'newsMovies' => $movieFrontendModel->getNewsMovies(),
                'dailyMovies' => $movieFrontendModel->getDailyShowings(),
                'settings' => $movieFrontendModel->getSiteSettings(),
            ];
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
