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
    

    public function createBooking($customerId, $data) {
        return $this->create('bookings', [
            'customer_id' => $customerId,
            'showing_id' => $data['showing_id'],
            'spots_reserved' => $data['spots'],
            'price_per_ticket' => $data['price_per_ticket'],
            'total_price' => $data['total_price'],
            'status' => 'confirmed'
        ]);
    }
}
