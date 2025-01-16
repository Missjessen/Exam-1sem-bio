<?php

class MovieDetailsController {
    private $movieModel;
    private $pageLoader;

    public function __construct($db) {
        $this->movieModel = new MovieFrontendModel($db); 
        $this->pageLoader = new PageLoader($db); 
    }

    public function showMovieDetails($slug) {
        try {
            if (empty($slug)) {
                throw new Exception("Slug mangler i URL'en.");
            }
    
            error_log("Slug modtaget: " . $slug); 
    
           
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
      
        $errorController = new ErrorController();
        $errorController->showErrorPage($message);
    }
}
