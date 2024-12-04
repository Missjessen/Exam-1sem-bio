<?php
require_once BASE_PATH . 'init.php';
class UserModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Hent kommende film baseret på premiere dato
    public function getUpcomingMovies() {
        $sql = "SELECT * FROM movies WHERE release_date >= CURDATE() ORDER BY release_date ASC LIMIT 5";
        return $this->db->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    // Hent de 10 nyeste film
    public function getNewsMovies() {
        $sql = "SELECT * FROM movies ORDER BY release_date DESC LIMIT 10";
        return $this->db->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    // Hent top 5 bedst bedømte film
    public function getTop5Movies() {
        $sql = "SELECT * FROM movies ORDER BY rating DESC LIMIT 5";
        return $this->db->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    // Hent alle film, eventuelt filtreret efter genre
    public function getAllMovies($genre = '') {
        if ($genre) {
            $sql = "SELECT * FROM movies WHERE genre = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('s', $genre);
        } else {
            $sql = "SELECT * FROM movies";
            $stmt = $this->db->prepare($sql);
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Metode til at hente en film baseret på slug
public function getMovieBySlug($slug) {
    $stmt = $this->db->prepare("SELECT * FROM movies WHERE slug = :slug");
    $stmt->bindParam(':slug', $slug);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
}

