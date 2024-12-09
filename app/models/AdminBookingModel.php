<?php
require_once 'init.php';
class AdminBookingModel extends CrudBase {
    // Booking-funktioner
    public function getAllBookings() {
        return $this->readWithJoin(
            'bookings',
            'bookings.booking_id, movies.title AS movie_title, customers.name AS customer_name, spots.spot_number, bookings.booking_date, bookings.price',
            [
                'JOIN movies ON bookings.movie_id = movies.id',
                'JOIN customers ON bookings.customer_id = customers.id',
                'JOIN spots ON bookings.spot_id = spots.spot_id'
            ]
        );
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
