<?php

class MovieFrontendController {
    private $model;

    public function __construct($db) {
        $this->model = new MovieFrontendModel($db); // Initialiser modellen her
    }

    public function showMovieDetails($slug) {
        error_log("Slug modtaget: $slug"); // Debug

        // Tjek om modellen er korrekt initialiseret
        if (!$this->model) {
            throw new Exception("Model ikke initialiseret!");
        }

        $movie = $this->model->getMovieDetailsBySlug($slug); // Brug slug
        if (!$movie) {
            header("HTTP/1.0 404 Not Found");
            require_once __DIR__ . '/../view/errors/404.php';
            exit();
        }

        // Hent visningstider baseret på movie_id
        $showtimes = $this->model->getShowingsForMovie($movie['id']);
        error_log("Showtimes fundet: " . print_r($showtimes, true)); // Debug

        // Indlæs view
        require_once __DIR__ . '/../view/user/movieDetails.php';
    }
}
