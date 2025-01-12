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

    // Hent en specifik booking
    public function getBookingByOrderNumber($orderNumber) {
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
        $where = ['b.order_number' => $orderNumber];
        return $this->readWithJoin('bookings b', $columns, $joins, $where, true);
    }

    // Opdater en booking
    public function updateBooking($orderNumber, $data) {
        $where = ['order_number' => $orderNumber];
        return $this->update('bookings', $data, $where);
    }

    // Slet en booking
    public function deleteBooking($orderNumber) {
        $where = ['order_number' => $orderNumber];
        return $this->delete('bookings', $where);
    }
}
