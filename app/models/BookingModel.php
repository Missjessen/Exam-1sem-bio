<?php

class BookingModel extends CrudBase {
    public function getShowingDetails($showingId) {
        $query = "
            SELECT 
                s.id AS showing_id,
                s.show_date,
                s.show_time,
                s.price_per_ticket,
                m.title AS movie_title
            FROM 
                showings s
            JOIN 
                movies m ON s.movie_id = m.id
            WHERE 
                s.id = :showing_id
        ";
    
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':showing_id', $showingId, PDO::PARAM_INT);
    
        try {
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            error_log("Result from getShowingDetails: " . print_r($result, true));
            return $result;
        } catch (PDOException $e) {
            throw new Exception("Fejl ved hentning af visning: " . $e->getMessage());
        }
    }
    

    // Opret en ny booking
    public function createBooking($customerId, $bookingData) {
        $query = "
            INSERT INTO bookings (customer_id, showing_id, spots_reserved, total_price, price_per_ticket, status)
            VALUES (:customer_id, :showing_id, :spots_reserved, :total_price, :price_per_ticket, 'confirmed')
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':customer_id', $customerId, PDO::PARAM_INT);
        $stmt->bindParam(':showing_id', $bookingData['showing_id'], PDO::PARAM_INT);
        $stmt->bindParam(':spots_reserved', $bookingData['spots'], PDO::PARAM_INT);
        $stmt->bindParam(':total_price', $bookingData['total_price'], PDO::PARAM_STR);
        $stmt->bindParam(':price_per_ticket', $bookingData['price_per_ticket'], PDO::PARAM_STR);
        return $stmt->execute();
    }
    

    // Hent bookinger for en specifik bruger
    public function getBookingsByUser($userId) {
        $query = "
            SELECT 
                b.id AS booking_id,
                m.title AS movie_title,
                s.show_date,
                s.show_time,
                b.spots_reserved,
                b.total_price,
                b.status
            FROM 
                bookings b
            JOIN 
                showings s ON b.showing_id = s.id
            JOIN 
                movies m ON s.movie_id = m.id
            WHERE 
                b.customer_id = :customer_id
            ORDER BY 
                s.show_date DESC, s.show_time DESC
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':customer_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

