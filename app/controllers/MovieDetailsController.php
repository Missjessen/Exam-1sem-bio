<?php

class MovieDetailsController {
    private $movieModel;
    private $pageLoader;

    public function __construct($db) {
        $this->movieModel = new MovieDetailsModel($db);
        $this->pageLoader = new PageLoader($db);
    }

    public function showMovieDetailsBySlug($slug) {
        try {
            $movie = $this->movieModel->getMovieDetailsBySlug($slug);
            if (!$movie) {
                throw new Exception("Ingen data fundet for slug: $slug");
            }
    
            $showtimes = $this->movieModel->getShowtimesForMovie($movie['id']);
    
            $this->pageLoader->loadUserPage('movie_details', [
                'movie' => $movie,
                'showtimes' => $showtimes
            ]);
        } catch (Exception $e) {
            $this->handleError("Fejl: " . $e->getMessage());
        }
    }

    private function handleError($message) {
        $errorController = new ErrorController();
        $errorController->showErrorPage($message); // Kald korrekt metode
    }
    
}