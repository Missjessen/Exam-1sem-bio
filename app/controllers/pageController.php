<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/init.php';

class PageController {
    private $pageLoader;
    private $movieAdminController;

    public function __construct($db) {
        $this->pageLoader = new PageLoader($db);
        error_log("PageLoader blev initialiseret.");
        $this->movieAdminController = new MovieAdminController($db);
        error_log("MovieAdminController blev initialiseret.");
    }

    // Indlæser brugersider
    public function showPage($page) {
        $this->pageLoader->loadUserPage($page);
    }
    public function showAdminMoviePage() {
        $movies = $this->movieAdminController->getAllMoviesWithDetails();
        error_log("Movies: " . print_r($movies, true));
    
        $actors = $this->movieAdminController->getAllActors();
        error_log("Actors: " . print_r($actors, true));
    
        $genres = $this->movieAdminController->getAllGenres();
        error_log("Genres: " . print_r($genres, true));
    
        $movieToEdit = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'edit') {
            $movieId = $_POST['movie_id'] ?? null;
            if ($movieId) {
                $movieToEdit = $this->movieAdminController->getMovieDetails($movieId);
                error_log("Movie to edit: " . print_r($movieToEdit, true));
            }
        }
    
        $this->pageLoader->loadAdminPage('admin_movie', compact('movies', 'actors', 'genres', 'movieToEdit'));
    }
}
