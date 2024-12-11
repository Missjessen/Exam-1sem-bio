<?php

class BookingModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Create a new booking
    public function createBooking($customerId, $movieId, $showtimeId, $spots, $price) {
        $query = "
            INSERT INTO bookings (customer_id, movie_id, showtime_id, spots, price) 
            VALUES (:customer_id, :movie_id, :showtime_id, :spots, :price)
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            ':customer_id' => $customerId,
            ':movie_id' => $movieId,
            ':showtime_id' => $showtimeId,
            ':spots' => $spots,
            ':price' => $price
        ]);

        return $this->db->lastInsertId();
    }

    // Get bookings for a specific customer
    public function getBookingsByCustomer($customerId) {
        $query = "
            SELECT 
                b.booking_id,
                m.title AS movie_title,
                s.show_date,
                s.show_time,
                b.spots,
                b.price
            FROM 
                bookings b
            JOIN 
                movies m ON b.movie_id = m.id
            JOIN 
                showings s ON b.showtime_id = s.id
            WHERE 
                b.customer_id = :customer_id
            ORDER BY 
                s.show_date, s.show_time
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':customer_id' => $customerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Delete a booking
    public function deleteBooking($bookingId) {
        $query = "DELETE FROM bookings WHERE booking_id = :booking_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':booking_id' => $bookingId]);
        return $stmt->rowCount() > 0;
    }

    // Update a booking
    public function updateBooking($bookingId, $spots, $price) {
        $query = "
            UPDATE bookings 
            SET spots = :spots, price = :price 
            WHERE booking_id = :booking_id
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            ':spots' => $spots,
            ':price' => $price,
            ':booking_id' => $bookingId
        ]);
        return $stmt->rowCount() > 0;
    }

    // Book spots and decrement available spots
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

    // Get showing details
    public function getShowingDetails($showingId) {
        $query = "
            SELECT id, movie_id, screen, total_spots, available_spots 
            FROM showings 
            WHERE id = :showing_id
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':showing_id', $showingId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Calculate the price
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

    public function getBookingDetails($bookingId) {
        $query = "
            SELECT 
                b.booking_id, 
                b.movie_id, 
                b.showtime_id, 
                b.price, 
                b.booking_date, 
                c.name AS customer_name,
                c.email AS customer_email,
                s.show_date, 
                s.show_time, 
                s.screen, 
                m.title AS movie_title 
            FROM 
                bookings b
            INNER JOIN 
                customers c ON b.customer_id = c.id
            INNER JOIN 
                showings s ON b.showtime_id = s.id
            INNER JOIN 
                movies m ON b.movie_id = m.id
            WHERE 
                b.booking_id = :booking_id
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':booking_id', $bookingId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
