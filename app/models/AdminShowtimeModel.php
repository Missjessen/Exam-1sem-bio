<?php
require_once BASE_PATH . 'init.php';

class ShowtimeModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function addShowtime($movieId, $date, $time) {
        $sql = "INSERT INTO Showtimes (showtime_id, movie_id, date, time) VALUES (UUID(), :movie_id, :date, :time)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':movie_id' => $movieId,
            ':date' => $date,
            ':time' => $time
        ]);
    }
}
