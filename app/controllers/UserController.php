<?php
require_once BASE_PATH . '/init.php'; // Inkluder init.php med $db og autoloader

class UserController {
    private $userModel;

    public function __construct($userModel) {
        $this->userModel = $userModel;
    }


    public function renderHomePage() {
        $upcomingMovies = $this->userModel->getUpcomingMovies();
        $newsMovies = $this->userModel->getNewsMovies();
        $top5Movies = $this->userModel->getTop5Movies();
        $allMovies = $this->userModel->getAllMovies();

        require 'app/view/homePage.php';
    }


    // Håndter visning af kommende film
    public function upcomingMovies() {
        $movies = $this->userModel->getUpcomingMovies();
        require 'app/view/user/upcoming.php';
    }

    // Håndter visning af de nyeste film
    public function newsMovies() {
        $movies = $this->userModel->getNewsMovies();
        require 'app/view/user/news.php';
    }

    // Håndter visning af top 5 film
    public function top5Movies() {
        $movies = $this->userModel->getTop5Movies();
        require 'app/view/user/top5.php';
    }

    // Håndter visning af alle film med mulighed for filtrering efter genre
    public function allMovies($genre = '') {
        $movies = $this->userModel->getAllMovies($genre);
        require 'app/view/user/all_movies.php';
    }
    public function movieDetails($uuid) {
        $movie = $this->userModel->getMovieByUUID($uuid);
        if (!$movie) {
            // Håndter fejl, hvis filmen ikke findes
            header("HTTP/1.0 404 Not Found");
            exit;
        }
        require 'app/view/user/movie_details.php';
    }
    
// Håndter visning af en specifik film baseret på slug
public function showMovie($slug) {
    $movie = $this->userModel->getMovieBySlug($slug);
    if (!$movie) {
        // Hvis filmen ikke findes, vis en 404-fejl
        header("HTTP/1.0 404 Not Found");
        echo "<p>Film ikke fundet.</p>";
        exit;
    }
    
    // Gør filmen tilgængelig i movie_details.php
    require 'app/view/user/movie_details.php';


}
}