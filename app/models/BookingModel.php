<?php 
class BookingModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function createBooking($showtimeId, $spots) {
        $this->db->beginTransaction();
        try {
            // Indsæt booking
            $stmt = $this->db->prepare("
                INSERT INTO bookings (showtime_id, spots)
                VALUES (:showtime_id, :spots)
            ");
            $stmt->execute(['showtime_id' => $showtimeId, 'spots' => $spots]);

            // Reducer ledige pladser
            $stmt = $this->db->prepare("
                UPDATE showings
                SET total_spots = total_spots - :spots
                WHERE id = :showtime_id AND total_spots >= :spots
            ");
            $stmt->execute(['showtime_id' => $showtimeId, 'spots' => $spots]);

            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
