<?php

class PageUserController {
    private $model;

    public function __construct(MovieFrontendModel $model) {
        $this->model = $model;
    }

    public function showHomePage() {

        $settings = $this->model->getSiteSettings();
        // Hent data fra modellen
        $upcomingMovies = $this->model->getUpcomingMovies() ?? [];
        $newsMovies = $this->model->getNewsMovies() ?? [];
        $dailyMovies = $this->model->getDailyShowings() ?? [];
        $genreMovies = $this->model->getGenreMovies() ?? [];
      

        // Inkludér view
        require_once __DIR__ . '/../view/user/homePage.php';
    }
}
