<?php

class MovieFrontendController {
    private $model;
    //private $recipientEmail = "nsj@cruise-nights-cinema.dk";
    public function showMovieDetails($slug) {
        error_log("Slug modtaget: " . $slug);
    
        $movie = $this->model->getMovieDetailsBySlug($slug);
        if (!$movie) {
            error_log("Film ikke fundet for slug: " . $slug);
            header("HTTP/1.0 404 Not Found");
            require_once __DIR__ . '/../view/errors/404.php';
            exit();
        }
    
        error_log("Film fundet: " . print_r($movie, true));
    
        $showtimes = $this->model->getShowingsForMovie($movie['id']);
        error_log("Visningstider: " . print_r($showtimes, true));
    
        require_once __DIR__ . '/../view/user/movieDetails.php';
    }
}