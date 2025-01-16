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
            m.poster
        FROM 
            movies m
        WHERE 
            m.slug = :slug
    ";
    
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
    
    try {
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        error_log("Resultat fra databasen: " . print_r($result, true)); 
        return $result;
    } catch (PDOException $e) {
        throw new Exception("Fejl ved hentning af film: " . $e->getMessage());
    }
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
    AND CONCAT(show_date, ' ', show_time) > NOW()
ORDER BY 
    show_date, show_time
    ";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':movie_id', $movieId, PDO::PARAM_INT);

    try {
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new Exception("Fejl ved hentning af visninger: " . $e->getMessage());
    }
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
    
        return $stmt->rowCount() > 0; 
    }
}
