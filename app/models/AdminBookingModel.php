<?php

class AdminBookingModel extends CrudBase {
    public function __construct($db) {
        parent::__construct($db);
    }

    // Hent alle bookinger
    public function getAllBookings() {
        $columns = "
            b.id, b.order_number, b.spots_reserved, b.status, b.total_price,
            c.name AS customer_name,
            m.title AS movie_title,
            s.show_date, s.show_time
        ";
        $joins = [
            "JOIN customers c ON b.customer_id = c.id",
            "JOIN showings s ON b.showing_id = s.id",
            "JOIN movies m ON s.movie_id = m.id"
        ];
        return $this->readWithJoin('bookings b', $columns, $joins);
    }

    public function getBookingByOrderNumber($orderNumber) {
        $sql = "SELECT 
                    b.id AS booking_id,
                    b.order_number,
                    c.name AS customer_name,
                    m.title AS movie_title,
                    s.show_date,
                    s.show_time,
                    b.spots_reserved,
                    b.status
                FROM bookings b
                JOIN customers c ON b.customer_id = c.id
                JOIN showings s ON b.showing_id = s.id
                JOIN movies m ON s.movie_id = m.id
                WHERE b.order_number = :order_number";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':order_number', $orderNumber, PDO::PARAM_STR);
        $stmt->execute();
    
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    

    // Opdater en booking
    public function updateBooking($orderNumber, $data) {
        $sql = "UPDATE bookings 
                SET spots_reserved = :spots_reserved, status = :status 
                WHERE order_number = :order_number";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':spots_reserved', $data['spots_reserved'], PDO::PARAM_INT);
        $stmt->bindParam(':status', $data['status'], PDO::PARAM_STR);
        $stmt->bindParam(':order_number', $orderNumber, PDO::PARAM_STR);
    
        return $stmt->execute();
    }
    

    // Slet en booking
    public function deleteBooking($orderNumber) {
        $where = ['order_number' => $orderNumber];
        return $this->delete('bookings', $where);
    }
}
