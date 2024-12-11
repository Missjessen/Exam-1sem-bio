<?php 

class BookingController {
    private $bookingModel;

    public function __construct($db) {
        $this->bookingModel = new BookingModel($db);
    }

    public function handleBooking($postData) {
        try {
            // Valider data
            if (empty($postData['showing_id']) || empty($postData['spots']) || empty($postData['customer_id'])) {
                throw new Exception('Ugyldige data. Vælg venligst en visning og antal pladser.');
            }

            $showingId = (int)$postData['showing_id'];
            $spots = (int)$postData['spots'];
            $customerId = (int)$postData['customer_id'];

            // Hent visningsdata
            $showing = $this->bookingModel->getShowingDetails($showingId);
            if (!$showing) {
                throw new Exception('Visning ikke fundet.');
            }

            // Beregn prisen
            $price = $this->bookingModel->calculatePrice($showing['screen'], $postData['row_type'], $spots);

            // Book pladser
            $success = $this->bookingModel->bookSpot($showingId, $spots);
            if (!$success) {
                throw new Exception('Kunne ikke booke pladser. Ikke nok ledige pladser.');
            }

            // Opret booking
            $this->bookingModel->createBooking($customerId, $showing['movie_id'], $showingId, $price, $spots);

            // Redirect til kvittering
            header('Location: /receipt.php?booking_success=true');
            exit;

        } catch (Exception $e) {
            $this->handleError($e->getMessage());
        }
    }

    private function handleError($message) {
        $errorController = new ErrorController();
        $errorController->showErrorPage($message);
    }
}
