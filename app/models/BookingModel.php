<?php
class BookingModel extends CrudBase {
    public function __construct($db) {
        parent::__construct($db);
    }

    // Opret en ny booking
    public function createBooking($movieId, $spotId, $customerId, $showtimeId, $price) {
        $data = [
            'movie_id' => $movieId,
            'spot_id' => $spotId,
            'customer_id' => $customerId,
            'showtime_id' => $showtimeId,
            'price' => $price,
        ];
        return $this->create('bookings', $data);
    }

    // Hent en kundes bookinger
    public function getCustomerBookings($customerId) {
        $joins = [
            "JOIN movies m ON bookings.movie_id = m.id",
            "JOIN showings s ON bookings.showtime_id = s.id"
        ];
        return $this->readWithJoin('bookings', 'bookings.*, m.title AS movie_title, s.show_date, s.show_time', $joins, ['bookings.customer_id' => $customerId]);
    }

    // Slet en booking
    public function deleteBooking($bookingId) {
        return $this->delete('bookings', ['booking_id' => $bookingId]);
    }
}
