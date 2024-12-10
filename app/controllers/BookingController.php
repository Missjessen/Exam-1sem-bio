<?php
class BookingController {
    private $bookingModel;

    public function __construct($db) {
        $this->bookingModel = new BookingModel($db);
    }

    public function createBooking($data) {
        try {
            $this->bookingModel->createBooking($data);
            header("Location: receipt.php?booking_success=true");
            exit;
        } catch (Exception $e) {
            error_log("Fejl ved booking: " . $e->getMessage());
            header("Location: receipt.php?booking_success=false");
            exit;
        }
    }
}
v