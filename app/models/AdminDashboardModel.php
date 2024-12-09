<?php

class AdminDashboardModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getDailyShowings() {
        $query = "SELECT * FROM daily_showings";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getNewsMovies() {
        $query = "SELECT * FROM news_movies";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
