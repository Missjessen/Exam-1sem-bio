<?php
class BookingModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function createBooking($data) {
        $stmt = $this->db->prepare("
            INSERT INTO bookings (movie_id, showtime_id, customer_id, price)
            VALUES (:movie_id, :showtime_id, :customer_id, :price)
        ");

        $stmt->execute([
            ':movie_id' => $data['movie_id'],
            ':showtime_id' => $data['showtime_id'],
            ':customer_id' => $data['customer_id'],
            ':price' => $data['price']
        ]);
    }
}
