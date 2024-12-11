<?php

class MovieDetailsModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getMovieDetailsBySlug($slug) {
        $query = "
            SELECT 
                m.id, 
                m.slug, 
                m.title, 
                m.description, 
                m.poster, 
                IFNULL(GROUP_CONCAT(DISTINCT g.name SEPARATOR ', '), '') AS genre, 
                IFNULL(GROUP_CONCAT(DISTINCT a.name SEPARATOR ', '), '') AS actors
            FROM 
                movies m
            LEFT JOIN 
                movie_genre mg ON m.id = mg.movie_id
            LEFT JOIN 
                genres g ON mg.genre_id = g.id
            LEFT JOIN 
                movie_actor ma ON m.id = ma.movie_id
            LEFT JOIN 
                actors a ON ma.actor_id = a.id
            WHERE 
                m.slug = :slug
            GROUP BY 
                m.id";
    
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
        $stmt->execute();
    
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getShowtimesForMovie($movieId) {
        $stmt = $this->db->prepare("
            SELECT 
                showtime_id, show_date, show_time, screen 
            FROM 
                showtimes 
            WHERE 
                movie_id = :movie_id
        ");
        $stmt->execute(['movie_id' => $movieId]);
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public function getAvailableShowtimes($movieId) {
        $query = "
            SELECT 
                showtime_id, show_date, show_time 
            FROM 
                showings 
            WHERE 
                movie_id = :movie_id
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':movie_id', $movieId, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
