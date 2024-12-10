<?php 

class BookingController {
    private $bookingModel;

    public function __construct($db) {
        $this->bookingModel = new BookingModel($db);
    }

    public function createBooking() {
        try {
            $showtimeId = $_POST['showtime_id'] ?? null;
            $spots = $_POST['spots'] ?? 1;

            if (!$showtimeId || !$spots) {
                throw new Exception("Manglende data til booking.");
            }

            $this->bookingModel->createBooking($showtimeId, $spots);

            header("Location: receipt.php?booking_success=true");
            exit;
        } catch (Exception $e) {
            error_log("Fejl under booking: " . $e->getMessage());
            header("Location: receipt.php?booking_success=false");
            exit;
        }
    }
}
