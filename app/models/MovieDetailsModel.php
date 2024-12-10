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
                GROUP_CONCAT(DISTINCT g.name SEPARATOR ', ') AS genre, 
                GROUP_CONCAT(DISTINCT a.name SEPARATOR ', ') AS actors
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
        $query = "
            SELECT 
                s.id AS showing_id,
                s.show_date,
                s.show_time,
                s.screen,
                s.available_spots
            FROM 
                showings s
            WHERE 
                s.movie_id = :movie_id
            ORDER BY 
                s.show_date ASC, s.show_time ASC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':movie_id', $movieId, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
