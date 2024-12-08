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
    
    public function getMoviesByGenre($genreName) {
        $query = "SELECT m.id, m.title, m.poster 
                  FROM movies m
                  JOIN movie_genre mg ON m.id = mg.movie_id
                  JOIN genres g ON mg.genre_id = g.id
                  WHERE g.name = :genre";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':genre', $genreName, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    public function getRandomGenreMovies($limit = 5) {
        $sql = "SELECT 
                    m.id AS movie_id, 
                    m.title, 
                    m.poster, 
                    GROUP_CONCAT(g.name SEPARATOR ', ') AS genres
                FROM movies m
                JOIN movie_genre mg ON m.id = mg.movie_id
                JOIN genres g ON mg.genre_id = g.id
                GROUP BY m.id
                ORDER BY RAND()
                LIMIT :limit";
    
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    

    /* public function getDailyShowings() {
        $sql = "SELECT 
                    m.id AS movie_id, 
                    m.title, 
                    m.poster AS image, 
                    s.show_date, 
                    s.show_time
                FROM movies m
                JOIN showings s ON m.id = s.movie_id
                WHERE s.show_date = CURDATE()
                ORDER BY s.show_time ASC";
    
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
 */
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

    // Brug CURDATE(), hvis ingen dato er angivet
    $date = $filterDate ?? date('Y-m-d');
    
    // Forbered og bind parametre
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':date', $date, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
    

    public function getGenreMovies($genre = null) {
        if ($genre) {
            // Hvis en genre er specificeret, hent film med den genre
            $query = "SELECT m.id AS movie_id, m.title, m.poster, g.name AS genre
                      FROM movies m
                      JOIN movie_genre mg ON m.id = mg.movie_id
                      JOIN genres g ON mg.genre_id = g.id
                      WHERE g.name = :genre
                      LIMIT 5";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':genre', $genre, PDO::PARAM_STR);
        } else {
            // Hent 5 tilfældige film
            $query = "SELECT m.id AS movie_id, m.title, m.poster, g.name AS genre
                      FROM movies m
                      JOIN movie_genre mg ON m.id = mg.movie_id
                      JOIN genres g ON mg.genre_id = g.id
                      ORDER BY RAND()
                      LIMIT 5";
            $stmt = $this->db->prepare($query);
        }

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
            return []; // Returner en tom array som fallback
        }
    }
    

    public function getNewsMovies() {
        $query = "SELECT slug, title, poster, premiere_date FROM movies WHERE premiere_date <= CURDATE() ORDER BY premiere_date DESC LIMIT 5";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        var_dump($result); // Debugging
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
}