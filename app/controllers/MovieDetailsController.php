<?php

class MovieDetailsController {
    private $movieModel;
    private $pageLoader;

    public function __construct($db) {
        $this->movieModel = new MovieDetailsModel($db);
        $this->pageLoader = new PageLoader($db);
    }

    public function showMovieDetailsBySlug($slug) {
        if (empty($slug)) {
            throw new Exception("Slug mangler. URL er forkert.");
        }
    
        try {
            // Hent filmens detaljer
            $movie = $this->movieModel->getMovieDetailsBySlug($slug);
            if (!$movie) {
                throw new Exception("Filmen med slug '{$slug}' blev ikke fundet.");
            }
    
            // Hent spilletider for filmen
            $showtimes = $this->movieModel->getAvailableShowtimes($movie['id']);
    
            // Indlæs siden med data
            $this->pageLoader->loadUserPage('movie_details', [
                'movie' => $movie,
                'showtimes' => $showtimes,
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