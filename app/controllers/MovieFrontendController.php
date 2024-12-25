<?php

class MovieFrontendController {
    private $model;
    //private $recipientEmail = "nsj@cruise-nights-cinema.dk";


    
    public function showMovieDetails($slug) {
        $movie = $this->model->getMovieDetailsBySlug($slug); // Brug slug i stedet for uuid
        if (!$movie) {
            header("HTTP/1.0 404 Not Found");
            require_once __DIR__ . '/../view/errors/404.php';
            exit();
        }
    
        // Hent visningstider baseret på movie_id
        $showtimes = $this->model->getShowingsForMovie($movie['id']);
    
        // Indlæs visning (view)
        require_once __DIR__ . '/../view/user/movieDetails.php';
    }
}