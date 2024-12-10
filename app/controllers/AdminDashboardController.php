<?php

class AdminDashboardController {
    private $model;
    private $pageLoader;

    public function __construct($db) {
        $this->model = new AdminDashboardModel($db);
        $this->pageLoader = new PageLoader($db);
    }

    public function showDashboard() {
        try {
            // Fetch data
            $dailyShowings = $this->model->getDailyShowings();
            $newsMovies = $this->model->getNewsMovies();

            // Debug logs
            error_log("Daily Showings: " . print_r($dailyShowings, true));
            error_log("News Movies: " . print_r($newsMovies, true));

            // Load the page
            $this->pageLoader->loadAdminPage('admin_dashboard', [
                'dailyShowings' => $dailyShowings,
                'newsMovies' => $newsMovies,
            ]);
        } catch (Exception $e) {
            error_log("Failed to load the dashboard: " . $e->getMessage());
            $this->pageLoader->renderErrorPage(500, "Failed to load the dashboard.");
        }
    }
}
