<?php

class MovieFrontendModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getUpcomingMovies() {
        $query = "SELECT * FROM upcoming_movies ORDER BY release_date ASC";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public function getNewsMovies() {
        $query = "SELECT * FROM news_movies ORDER BY release_date DESC";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public function getDailyShowings() {
        $query = "SELECT * FROM daily_showings ORDER BY show_time ASC";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getRandomGenreMovies() {
        $query = "SELECT id, title, poster, genre FROM genre_movies 
                  ORDER BY RAND() 
                  LIMIT 5";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMoviesByGenre($genre) {
        $query = "SELECT * FROM genre_movies WHERE genre = :genre";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':genre', $genre, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public function getSiteSettings() {
        $query = "SELECT setting_key, setting_value FROM site_settings";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
    
        $settings = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
    
        return $settings;
    }
    

    public function getMovieByUuid($uuid) {
        $query = "SELECT * FROM movies WHERE uuid = :uuid LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':uuid', $uuid, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
