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
    
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Fejl ved hentning af film: " . $e->getMessage());
        }
    
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    

    public function getShowingsForMovie($movieId) {
        $query = "
            SELECT 
                id AS showing_id,
                screen,
                show_date,
                show_time,
                total_spots,
                available_spots
            FROM 
                showings
            WHERE 
                movie_id = :movie_id
            ORDER BY 
                show_date, show_time
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':movie_id', $movieId, PDO::PARAM_STR);
    
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Fejl ved hentning af visninger: " . $e->getMessage());
        }
    
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

 
    public function bookSpot($showingId, $spots) {
        $query = "
            UPDATE showings
            SET available_spots = available_spots - :spots
            WHERE id = :showing_id AND available_spots >= :spots
        ";
    
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':showing_id', $showingId, PDO::PARAM_INT);
        $stmt->bindParam(':spots', $spots, PDO::PARAM_INT);
    
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Fejl ved booking: " . $e->getMessage());
        }
    
        return $stmt->rowCount() > 0; // Returner true, hvis en række blev opdateret
    }
}
