<?php

class MovieFrontendModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }
    public function getAllGenres() {
        $query = "SELECT name FROM genres";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }
    


    public function getShowingsForMovie($movieId) {
        error_log("Henter visninger for film med ID: $movieId"); 
    
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
    
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        error_log("Visninger hentet: " . print_r($result, true)); 
        return $result;
    }

    
    

public function getDailyShowings($filterDate = null) {
    $query = "SELECT 
                s.id AS showing_id,
                m.id AS movie_id,
                m.slug,
                m.title AS movie_title,
                m.poster,
                s.show_date,
                s.show_time,
                s.screen,
                GROUP_CONCAT(DISTINCT g.name SEPARATOR ', ') AS genres
              FROM 
                showings s
              JOIN 
                movies m ON s.movie_id = m.id
              LEFT JOIN 
                movie_genre mg ON m.id = mg.movie_id
              LEFT JOIN 
                genres g ON mg.genre_id = g.id
              WHERE 
                s.show_date = :date
              GROUP BY 
                s.id, m.id, m.slug, m.title, m.poster, s.show_date, s.show_time, s.screen
              ORDER BY 
                s.show_time ASC";

   
    $date = $filterDate ?? date('Y-m-d');
    
   
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':date', $date, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
    



    public function getUpcomingMovies() {
        $query = "SELECT slug, title, poster, premiere_date 
                  FROM movies 
                  WHERE premiere_date >= CURDATE() 
                  ORDER BY premiere_date ASC";
        try {
            $stmt = $this->db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("SQL Fejl i getUpcomingMovies(): " . $e->getMessage());
            return []; 
        }
    }
    

    public function getNewsMovies() {
        $query = "SELECT slug, title, poster, premiere_date FROM movies WHERE premiere_date <= CURDATE() ORDER BY premiere_date DESC LIMIT 5";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
       
        return $result;
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

    public function getMovieDetailsBySlug($slug) {
        try {
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
            
            $movie = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($movie) {
                error_log("Film fundet: " . print_r($movie, true)); 
            } else {
                error_log("Ingen film fundet for slug: " . $slug); 
            }
    
            return $movie;
        } catch (PDOException $e) {
            error_log("Fejl i getMovieDetailsBySlug: " . $e->getMessage());
            return null;
        }
    }
    
}