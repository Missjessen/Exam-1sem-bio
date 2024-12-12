<?php
class BookingController {
    private $bookingModel;

    public function __construct($bookingModel) {
        $this->bookingModel = $bookingModel;
    }

    public function handleBooking($postData) {
        $customerId = $_SESSION['user_id'];
        $movieId = $postData['movie_id'];
        $spotId = $postData['spot_id'];
        $showtimeId = $postData['showtime_id'];
        $price = $postData['price'];

        return $this->bookingModel->createBooking($movieId, $spotId, $customerId, $showtimeId, $price);
    }

    public function getCustomerBookings($customerId) {
        return $this->bookingModel->getCustomerBookings($customerId);
    }
}
