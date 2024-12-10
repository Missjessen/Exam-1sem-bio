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

            // Hent visninger for filmen
            $showtimes = $this->movieModel->getShowtimesForMovie($movie['id']);
    
            // Indlæs siden med de nødvendige data
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
        //$errorController->showErrorPage($message);
    }
}
