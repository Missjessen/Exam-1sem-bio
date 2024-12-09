<?php
class AdminBookingController {
    private $bookingModel;

    public function __construct($db) {
        $this->bookingModel = new AdminBookingModel($db);
    }

    // Hent alle bookinger og returner til view
    public function listBookings() {
        return $this->bookingModel->getAllBookings();
    }

    // Håndter oprettelse af ny booking
    public function createBooking($data) {
        if ($this->bookingModel->createBooking($data)) {
            return "Booking oprettet succesfuldt.";
        }
        return "Fejl ved oprettelse af booking.";
    }

    // Håndter opdatering af booking
    public function updateBooking($id, $data) {
        if ($this->bookingModel->updateBooking($id, $data)) {
            return "Booking opdateret succesfuldt.";
        }
        return "Fejl ved opdatering af booking.";
    }

    // Håndter sletning af booking
    public function deleteBooking($id) {
        if ($this->bookingModel->deleteBooking($id)) {
            return "Booking slettet succesfuldt.";
        }
        return "Fejl ved sletning af booking.";
    }
}
