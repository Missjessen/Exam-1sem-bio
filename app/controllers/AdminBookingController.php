
<?php
require_once BASE_PATH . 'init.php';
class AdminBookingController {
    private $model;
    private $adminController;
    private $movieAdminController;
    

    public function __construct(AdminBookingModel $model, AdminController $adminController, MovieAdminController $movieAdminController) {
        $this->model = $model;
        $this->adminController = $adminController;
        $this->movieAdminController = $movieAdminController;
    }

    // Håndter booking CRUD
    public function handleBookingSubmission($postData, $getData) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($postData['add_or_update_booking'])) {
                $data = [
                    'customer_id' => $postData['customer_id'],
                    'parking_spot_id' => $postData['parking_spot_id'],
                    'movie_id' => $postData['movie_id'],
                    'booking_time' => $postData['booking_time'],
                    'spots_booked' => $postData['spots_booked'],
                    'total_price' => $postData['total_price']
                ];

                if (!empty($postData['id'])) {
                    $this->model->updateBooking($postData['id'], $data);
                } else {
                    $this->model->createBooking($data);
                }
            }
        }

        if (isset($getData['delete_booking_id'])) {
            $this->model->deleteBooking($getData['delete_booking_id']);
        }
    }

    // Generer faktura fra booking
    public function generateInvoice($bookingId) {
        $booking = $this->model->getBookingById($bookingId);
        if (!$booking) {
            throw new Exception("Booking ikke fundet.");
        }

        $invoiceData = [
            'booking_id' => $booking['id'],
            'total_price' => $booking['total_price']
        ];

        return $this->model->createInvoice($invoiceData);
    }

    public function searchBookings($search) {
        return $this->model->searchBookings($search);
    }
    

    public function getAllBookings() {
        return $this->model->getAllBookings();
    }

    public function getAllInvoices() {
        return $this->model->getAllInvoices();
    }

    public function getAllMovies() {
        return $this->movieAdminController->getAllMovies();
    }

    public function getAllParkingSpots() {
        return $this->model->getAllParkingSpots();
    }

    public function getAllCustomers() {
        return $this->adminController->getAllCustomers();
    }
}
