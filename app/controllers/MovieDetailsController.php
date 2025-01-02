<?php

class MovieDetailsController {
    private $movieModel;
    private $pageLoader;

    public function __construct($db) {
        $this->movieModel = new MovieFrontendModel($db); // Genbrug MovieFrontendModel
        $this->pageLoader = new PageLoader($db); // Genbrug PageLoader
    }

    public function showMovieDetails($slug) {
        try {
            if (empty($slug)) {
                throw new Exception("Slug mangler i URL'en.");
            }
    
            error_log("Slug modtaget: " . $slug); // Debug
    
            // Hent filmoplysninger baseret på slug
            $movie = $this->movieModel->getMovieDetailsBySlug($slug);
            if (!$movie) {
                throw new Exception("Filmen blev ikke fundet for slug: $slug");
            }
    
            // Hent visningstider baseret på movie_id
            $showtimes = $this->movieModel->getShowingsForMovie($movie['id']);
    
            // Render siden med data
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
