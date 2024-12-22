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
            // Valider, at slug ikke er tom
            if (empty($slug)) {
                throw new Exception("Slug mangler i URL'en.");
            }
            if (!preg_match('/^[a-zA-Z0-9-_]+$/', $slug)) {
                throw new Exception("Slug indeholder ugyldige tegn.");
            }
            

            // Hent filmens detaljer
            $movie = $this->movieModel->getMovieDetailsBySlug($slug);
            if (!$movie) {
                throw new Exception("Ingen data fundet for slug: $slug");
            }
    
            // Hent alle visninger for filmen
            $showtimes = $this->movieModel->getShowingsForMovie($movie['id']);
    
            // Indlæs siden med filmdata og visningstider
            $this->pageLoader->loadUserPage('movie_details', [
                'movie' => $movie,
                'showtimes' => $showtimes
            ]);
        } catch (Exception $e) {
            $this->handleError("Fejl: " . $e->getMessage());
        }
    }

    private function handleError($message) {
        // Brug ErrorController til at vise fejl
        $errorController = new ErrorController();
        $errorController->showErrorPage($message);
    }
}


