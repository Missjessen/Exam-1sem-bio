<?php

class AdminBookingModel extends CrudBase {
    public function getAllBookingsWithDetails() {
        $columns = "b.id AS booking_id, 
                    b.customer_id, 
                    c.name AS customer_name, 
                    b.showing_id, 
                    m.title AS movie_title, 
                    b.order_number, 
                    b.spots_reserved, 
                    b.status, 
                    s.show_date, 
                    s.show_time";
        $joins = [
            "INNER JOIN customers c ON b.customer_id = c.id",
            "INNER JOIN showings s ON b.showing_id = s.id",
            "INNER JOIN movies m ON s.movie_id = m.id"
        ];

        return $this->readWithJoin('bookings b', $columns, $joins);
    }

    public function createBooking($data) {
        return $this->create('bookings', $data);
    }

    public function updateBooking($data, $where) {
        return $this->update('bookings', $data, $where);
    }

    public function deleteBooking($where) {
        return $this->delete('bookings', $where);
    }
}
