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
            if (empty($slug)) {
                throw new Exception("Slug mangler i URL'en.");
            }
    
            error_log("Slug modtaget: " . $slug); // Debug
    
            $movie = $this->movieModel->getMovieDetailsBySlug($slug);
            if (!$movie) {
                throw new Exception("Filmen blev ikke fundet for slug: $slug");
            }
    
            $showtimes = $this->movieModel->getShowingsForMovie($movie['id']);
            $this->pageLoader->loadUserPage('movie_details', [
                'movie' => $movie,
                'showtimes' => $showtimes
            ]);
        } catch (Exception $e) {
            $this->handleError($e->getMessage());
        }
    }

    private function handleError($message) {
        // Brug ErrorController til at vise fejl
        $errorController = new ErrorController();
        $errorController->showErrorPage($message);
    }
}


