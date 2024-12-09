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
            $dailyShowings = $this->model->getDailyShowings();
            $newsMovies = $this->model->getNewsMovies();
    
            // Send data til PageLoader
            $this->pageLoader->loadAdminPage('admin_dashboard', [
                'dailyShowings' => $dailyShowings,
                'newsMovies' => $newsMovies
            ]);
        } catch (Exception $e) {
            $this->handleError("Fejl: " . $e->getMessage());
        }
    }
    
}
