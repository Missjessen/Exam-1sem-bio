<?php

class BookingModel extends CrudBase {
    public function getShowingDetails($showingId) {
        return $this->readWithJoin(
            'showings',
            'showings.*, movies.title AS movie_title',
            ['JOIN movies ON showings.movie_id = movies.id'],
            ['showings.id' => $showingId],
            true
        );
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
