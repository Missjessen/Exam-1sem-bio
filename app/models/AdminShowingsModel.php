<?php class AdminShowingsModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllShowings() {
        try {
            $sql = "SELECT 
                        s.id,
                        s.show_date,
                        s.show_time,
                        s.screen,
                        s.total_spots,
                        s.available_spots,
                        m.title AS movie_title
                    FROM showings s
                    JOIN movies m ON s.movie_id = m.id";
            $stmt = $this->db->query($sql);
            $showings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            error_log("Showings hentet fra DB: " . print_r($showings, true)); // Debugging
            return $showings;
        } catch (PDOException $e) {
            error_log("Fejl ved hentning af showings: " . $e->getMessage());
            return [];
        }
    }
    

    // Tilføj en visning
    public function addShowing($movieId, $showingTime, $screen) {
        $sql = "INSERT INTO showings (movie_id, showing_time, screen) VALUES (:movie_id, :showing_time, :screen)";
        $stmt = $this->db->prepare($sql);
    
        $stmt->bindValue(':movie_id', $movieId, PDO::PARAM_INT);
        $stmt->bindValue(':showing_time', $showingTime, PDO::PARAM_STR);
        $stmt->bindValue(':screen', $screen, PDO::PARAM_STR);
    
        if ($stmt->execute()) {
            return true;
        } else {
            error_log("Fejl ved indsættelse i databasen: " . implode(", ", $stmt->errorInfo()));
            return false;
        }
    }
    
    

    // Hent en visning ved id
    public function getShowingById($showingId) {
        $query = "SELECT s.id, m.title AS movie_title, s.showing_time, s.screen, s.movie_id
                  FROM showings s
                  JOIN movies m ON s.movie_id = m.id
                  WHERE s.id = :showing_id";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':showing_id', $showingId);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Database fejl ved hentning af visning: ' . $e->getMessage());
            return null;
        }
    }

    // Opdater en visning
    public function updateShowing($showingId, $movieId, $showingTime, $screen) {
        $query = "UPDATE showings SET movie_id = :movie_id, showing_time = :showing_time, screen = :screen
                  WHERE id = :showing_id";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':movie_id', $movieId);
            $stmt->bindParam(':showing_time', $showingTime);
            $stmt->bindParam(':screen', $screen);
            $stmt->bindParam(':showing_id', $showingId);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Database fejl ved opdatering af visning: ' . $e->getMessage());
            return false;
        }
    }

    // Slet en visning
    public function deleteShowing($showingId) {
        $query = "DELETE FROM showings WHERE id = :showing_id";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':showing_id', $showingId);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Database fejl ved sletning af visning: ' . $e->getMessage());
            return false;
        }
    }

    // Hent alle film (til visningstilføjelse)
    public function getAllMovies() {
        $query = "SELECT id, title FROM movies";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Database fejl ved hentning af film: ' . $e->getMessage());
            return [];
        }
    }
    public function getShowings() {
        $sql = "SELECT * FROM showings ORDER BY showing_time DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
