<?php 
class AdminShowingsModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllMovies() {
        $query = "SELECT id, title FROM movies";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllShowings() {
        $query = "SELECT st.showtime_id AS id, 
                         m.title AS movie_title, 
                         CONCAT(st.show_date, ' ', st.show_time) AS showing_time
                  FROM showtimes st
                  JOIN movies m ON st.movie_id = m.id";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addShowing($movieId, $showingTime) {
        $date = substr($showingTime, 0, 10);
        $time = substr($showingTime, 11);
    
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
        $query = "INSERT INTO showings (movie_id, show_date, show_time, total_spots, available_spots) 
                  VALUES (:movie_id, :show_date, :show_time, 50, 50)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':movie_id', $movieId);
        $stmt->bindParam(':show_date', $date);
        $stmt->bindParam(':show_time', $time);
    
        return $stmt->execute();
    }

    public function deleteShowing($showingId) {
        // Tjek om showing eksisterer
        $query = "SELECT COUNT(*) FROM showings WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $showingId);
        $stmt->execute();
        if ($stmt->fetchColumn() == 0) {
            echo "<pre>Error: Showing not found</pre>";
            return false;
        }
    
        // Slet visningen
        $query = "DELETE FROM showings WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $showingId);
        return $stmt->execute();
    }
}
