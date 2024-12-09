<?php

class AdminDashboardController {
    private $model;
    private $PageLoader;

    public function __construct($db) {
        $this->model = new AdminDashboardModel($db);
        $this->PageLoader = new PageLoader($db);
    }

    public function showDashboard() {
        try {
            $dailyShowings = $this->model->getDailyShowings();
            $newsMovies = $this->model->getNewsMovies();
    
            // Send data til PageLoader
            $this->PageLoader->loadAdminPage('admin_dashboard', [
                'dailyShowings' => $dailyShowings,
                'newsMovies' => $newsMovies
            ]);
        } catch (Exception $e) {
            $this->handleError("Fejl: " . $e->getMessage());
        }
    }
    
}
