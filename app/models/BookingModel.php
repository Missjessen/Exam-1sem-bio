<?php

class BookingModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getShowingDetails($showingId) {
        $query = "SELECT id, movie_id, screen, total_spots, available_spots FROM showings WHERE id = :showing_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':showing_id', $showingId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function calculatePrice($screen, $rowType, $spots) {
        $query = "
            SELECT price_per_spot 
            FROM parking_prices 
            WHERE screen = :screen AND row_type = :row_type
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':screen' => $screen, ':row_type' => $rowType]);
        $pricePerSpot = $stmt->fetchColumn();

        if (!$pricePerSpot) {
            throw new Exception("Pris for valgt skærm og række ikke fundet.");
        }

        return $pricePerSpot * $spots;
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
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public function createBooking($customerId, $movieId, $showtimeId, $price, $spots) {
        $query = "
            INSERT INTO bookings (customer_id, movie_id, showtime_id, price, spots) 
            VALUES (:customer_id, :movie_id, :showtime_id, :price, :spots)
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            ':customer_id' => $customerId,
            ':movie_id' => $movieId,
            ':showtime_id' => $showtimeId,
            ':price' => $price,
            ':spots' => $spots
        ]);

        return $this->db->lastInsertId();
    }
}
