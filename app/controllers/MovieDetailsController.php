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
            $movie = $this->movieModel->getMovieDetailsBySlug($slug);
            if (!$movie) {
                throw new Exception("Filmen med slug '{$slug}' blev ikke fundet.");
            }
    
            $this->pageLoader->loadUserPage('movie_details', ['movie' => $movie]);
        } catch (Exception $e) {
            $this->handleError("Fejl: " . $e->getMessage());
        }
    }

private function handleError($message) {
    $errorController = new ErrorController();
    $errorController->showErrorPage($message);
}
}