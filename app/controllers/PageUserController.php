<?php

class PageUserController {
    private $model;

    public function __construct(MovieFrontendModel $model) {
        $this->model = $model;
    }

    public function showHomePage() {
        try {
            // Hent data fra modellen
            $upcomingMovies = $this->model->getUpcomingMovies() ?? [];
            $newsMovies = $this->model->getNewsMovies() ?? [];
            $dailyMovies = $this->model->getDailyShowings() ?? [];
            $genreMovies = $this->model->getGenreMovies() ?? [];
            $settings = $this->model->getSiteSettings() ?? [];
            $randomGenreMovies = $this->model->getRandomGenreMovies();
            $allGenres = $this->model->getAllGenres(); // Hent alle genrer

            // Hent film for den valgte genre, hvis en genre er valgt
            $selectedGenre = $_GET['genre'] ?? null;
            $moviesByGenre = [];
            if ($selectedGenre) {
                $moviesByGenre = $this->model->getMoviesByGenre($selectedGenre);
            }

            // Inkludér view
            require_once __DIR__ . '/../view/user/homePage.php';
        } catch (Exception $e) {
            // Log fejlen og vis fallback-siden
            error_log($e->getMessage());
            require_once __DIR__ . '/../view/user/errorPage.php';
        }
    }
}
