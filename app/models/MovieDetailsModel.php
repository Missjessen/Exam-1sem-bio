<?php

class MovieDetailsModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

   public function getMovieDetailsBySlug($slug) {
    $query = "SELECT 
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

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Debugging: Log resultatet
    error_log("Result fra getMovieDetailsBySlug: " . print_r($result, true));

    if (!$result) {
        return false;
    }

    return $result;
}

public function getShowtimesForMovie($movieId) {
    $stmt = $this->db->prepare("
        SELECT 
            show_date, show_time, screen, total_spots
        FROM 
            showings
        WHERE 
            movie_id = :movie_id
        ORDER BY 
            show_date ASC, show_time ASC
    ");
    $stmt->execute(['movie_id' => $movieId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


}
