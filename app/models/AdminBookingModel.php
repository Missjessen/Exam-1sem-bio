<?php
require_once 'init.php';
class AdminBookingModel extends CrudBase {
    // Booking-funktioner
    public function getAllBookings() {
        $columns = "
            b.booking_id AS booking_id,
            b.booking_date AS booking_date,
            b.price AS price,
            b.status AS status,
            m.title AS movie_title,
            c.name AS customer_name,
            s.spot_number AS spot_number
        ";
        $joins = [
            "INNER JOIN movies m ON b.movie_id = m.id",
            "INNER JOIN customers c ON b.customer_id = c.id",
            "INNER JOIN spots s ON b.spot_id = s.id"
        ];
    
        try {
            return $this->readWithJoin('bookings b', $columns, $joins);
        } catch (PDOException $e) {
            error_log("Fejl i getAllBookings: " . $e->getMessage());
            return [];
        }
    }
    


    public function getAllMovies() {
        return $this->read('movies', 'id, title, price');
    }
    

    public function getBookingById($id) {
        return $this->read('bookings', '*', ['id' => $id], true);
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


    public function getAllParkingSpots() {
        return $this->read('parking_spots');
    }

    // Faktura-funktioner
    public function createInvoice($data) {
        return $this->create('invoices', $data);
    }

    public function getAllInvoices() {
        $joins = [
            "INNER JOIN bookings ON invoices.booking_id = bookings.id",
            "INNER JOIN customers ON bookings.customer_id = customers.id"
        ];
        return $this->readWithJoin('invoices', 
            'invoices.*, bookings.booking_time, customers.name AS customer_name',
            $joins
        );
    }

    public function getInvoiceById($id) {
        $joins = [
            "INNER JOIN bookings ON invoices.booking_id = bookings.id",
            "INNER JOIN movies ON bookings.movie_id = movies.id",
            "INNER JOIN customers ON bookings.customer_id = customers.id"
        ];
        return $this->readWithJoin('invoices', 
            'invoices.*, bookings.booking_time, movies.title AS movie_title, movies.price AS movie_price, customers.name AS customer_name',
            $joins,
            ['invoices.id' => $id],
            true
        );
    }

    public function searchBookings($search) {
        $joins = [
            "INNER JOIN customers ON bookings.customer_id = customers.id"
        ];
    
        $where = [
            'customers.email LIKE' => "%$search%",
            'OR customers.phone LIKE' => "%$search%"
        ];
    
        return $this->readWithJoin('bookings', 
            'bookings.*, customers.name AS customer_name, parking_spots.screen AS screen, movies.title AS movie_title',
            $joins,
            $where
        );
    }
    
}
