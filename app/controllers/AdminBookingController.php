<?php
class AdminBookingController {
    private $bookingModel;

    public function __construct($db) {
        $this->bookingModel = new AdminBookingModel($db);
    }


    public function handlePostRequest($data) {
        $action = $data['action'] ?? '';
        $bookingId = $data['booking_id'] ?? null;
    
        switch ($action) {
            case 'edit':
                // Implementer redigeringslogik her
                $this->updateBooking($bookingId, $data);
                break;
    
            case 'delete':
                // Slet booking
                $this->deleteBooking($bookingId);
                break;
        }
    }
    

    // Hent alle bookinger og returner til view
    public function listBookings() {
        // Hent alle bookinger fra modellen
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
