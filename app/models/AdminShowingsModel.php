<?php 
class AdminShowingsModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Hent alle film
    public function getAllMovies() {
        $query = "SELECT id, title FROM movies";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Hent alle visninger, sorteret med de nyeste først
    public function getAllShowings() {
        $query = "SELECT st.id AS id, 
                         m.title AS movie_title, 
                         CONCAT(st.show_date, ' ', st.show_time) AS showing_time
                  FROM showings st
                  JOIN movies m ON st.movie_id = m.id
                  ORDER BY st.show_date, st.show_time ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    // Tilføj en visning
    public function addShowing($movieId, $showingTime) {
        $date = substr($showingTime, 0, 10);  // Datoen fra datetime-local
        $time = substr($showingTime, 11);     // Tiden fra datetime-local
    
        // Valider at movie_id eksisterer
        $query = "SELECT COUNT(*) FROM movies WHERE id = :movie_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':movie_id', $movieId);
        $stmt->execute();
        if ($stmt->fetchColumn() == 0) {
            echo "<pre>Error: Invalid movie_id</pre>";
            return false;
        }
    
        // Indsæt visning
        $query = "INSERT INTO showtimes (movie_id, show_date, show_time, total_spots, available_spots) 
                  VALUES (:movie_id, :show_date, :show_time, 50, 50)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':movie_id', $movieId);
        $stmt->bindParam(':show_date', $date);
        $stmt->bindParam(':show_time', $time);
    
        return $stmt->execute();
    }

    // Slet en visning
    public function deleteShowing($showingId) {
        // Tjek om visning eksisterer
        $query = "SELECT COUNT(*) FROM showtimes WHERE showtime_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $showingId);
        $stmt->execute();
        if ($stmt->fetchColumn() == 0) {
            echo "<pre>Error: Showing not found</pre>";
            return false;
        }
    
        // Slet visningen
        $query = "DELETE FROM showtimes WHERE showtime_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $showingId);
        return $stmt->execute();
    }

    // Hent en specifik visning for at redigere
    public function getShowingById($showingId) {
        $query = "SELECT st.showtime_id AS id, 
                         m.title AS movie_title, 
                         CONCAT(st.show_date, ' ', st.show_time) AS showing_time
                  FROM showtimes st
                  JOIN movies m ON st.movie_id = m.id
                  WHERE st.showtime_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $showingId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Opdater en visning
    public function updateShowing($showingId, $movieId, $showingTime) {
        $date = substr($showingTime, 0, 10);
        $time = substr($showingTime, 11);
    
        // Opdater visningen
        $query = "UPDATE showtimes 
                  SET movie_id = :movie_id, show_date = :show_date, show_time = :show_time
                  WHERE showtime_id = :showtime_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':movie_id', $movieId);
        $stmt->bindParam(':show_date', $date);
        $stmt->bindParam(':show_time', $time);
        $stmt->bindParam(':showtime_id', $showingId);
        return $stmt->execute();
    }
}
