<?php 

class BookingController {
    private $bookingModel;
    private $db

    public function __construct($db) {
        $this->db = $db;
    
        $this->bookingModel = new BookingModel($db);
            $this->db = $db;
        }
    
        public function handleBooking() {
            session_start();
            if (!isset($_SESSION['user_id'])) {
                // Hvis brugeren ikke er logget ind, omdiriger til login
                $_SESSION['redirect_after_login'] = BASE_URL . 'index.php?page=booking';
                header("Location: " . BASE_URL . "index.php?page=login");
                exit;
            }
    
            // Booking-logik, hvis brugeren er logget ind
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $movieId = $_POST['movie_id'];
                $customerId = $_SESSION['user_id'];
                $bookingDate = date('Y-m-d H:i:s');
    
                $stmt = $this->db->prepare("INSERT INTO bookings (movie_id, customer_id, booking_date) VALUES (:movie_id, :customer_id, :booking_date)");
                $stmt->execute([
                    ':movie_id' => $movieId,
                    ':customer_id' => $customerId,
                    ':booking_date' => $bookingDate,
                ]);
    
                echo "Din booking er gennemført!";
            }
        }
    

    // Handle creating a booking
    public function handleBooking($postData) {
        try {
            // Validate input data
            if (empty($postData['showing_id']) || empty($postData['spots']) || empty($postData['customer_id'])) {
                throw new Exception('Ugyldige data. Vælg venligst en visning og antal pladser.');
            }

            $showingId = (int)$postData['showing_id'];
            $spots = (int)$postData['spots'];
            $customerId = (int)$postData['customer_id'];

            // Get showing details
            $showing = $this->bookingModel->getShowingDetails($showingId);
            if (!$showing) {
                throw new Exception('Visning ikke fundet.');
            }

            // Calculate price
            $price = $this->bookingModel->calculatePrice($showing['screen'], $postData['row_type'], $spots);

            // Book spots
            $success = $this->bookingModel->bookSpot($showingId, $spots);
            if (!$success) {
                throw new Exception('Kunne ikke booke pladser. Ikke nok ledige pladser.');
            }

            // Create booking
            $bookingId = $this->bookingModel->createBooking($customerId, $showing['movie_id'], $showingId, $price, $spots);

            // Redirect to receipt with booking ID
            header('Location: /?page=bookingAndReceipt&action=receipt&booking_id=' . $bookingId);
            exit;

        } catch (Exception $e) {
            $this->handleError($e->getMessage());
        }
    }

    // Get bookings for a specific customer (e.g., for their profile)
    public function getCustomerBookings($customerId) {
        try {
            return $this->bookingModel->getBookingsByCustomer($customerId);
        } catch (Exception $e) {
            $this->handleError($e->getMessage());
        }
    }

    // Delete a booking (admin functionality)
    public function deleteBooking($bookingId) {
        try {
            $this->bookingModel->deleteBooking($bookingId);
            header('Location: /?page=admin_bookings&action=delete&status=success');
            exit;
        } catch (Exception $e) {
            $this->handleError($e->getMessage());
        }
    }

    // Update a booking (admin functionality)
    public function updateBooking($bookingId, $postData) {
        try {
            $spots = (int)$postData['spots'];
            $price = (float)$postData['price'];
            $this->bookingModel->updateBooking($bookingId, $spots, $price);
            header('Location: /?page=admin_bookings&action=update&status=success');
            exit;
        } catch (Exception $e) {
            $this->handleError($e->getMessage());
        }
    }

    private function handleError($message) {
        $errorController = new ErrorController();
        $errorController->showErrorPage($message);
    }

    // Get booking details for receipt
    public function getBookingDetails($bookingId) {
        try {
            return $this->bookingModel->getBookingDetails($bookingId);
        } catch (Exception $e) {
            throw new Exception("Fejl ved hentning af bookingdetaljer: " . $e->getMessage());
        }
    }
}

