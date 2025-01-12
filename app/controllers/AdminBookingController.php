<?php

class AdminBookingController {
    private $model;
    private $pageLoader;

    public function __construct($db) {
        $this->model = new AdminBookingModel($db);
        $this->pageLoader = new PageLoader($db);
    }

    public function admin_bookings() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            $action = $_POST['action'];
            $orderNumber = $_POST['order_number'] ?? null;

            switch ($action) {
                case 'create':
                    $this->createBooking();
                    break;
                case 'edit':
                    $this->editBooking();
                    break;
                case 'delete':
                    $this->deleteBooking($orderNumber);
                    break;
                default:
                    $this->pageLoader->renderErrorPage(400, "Ugyldig handling.");
                    break;
            }
        } else {
            $this->showBookings();
        }
    }

    private function showBookings() {
        $bookings = $this->model->getAllBookingsWithDetails();
        $this->pageLoader->renderPage('admin_bookings', ['bookings' => $bookings], 'admin');
    }

    private function createBooking() {
        $data = [
            'customer_id' => $_POST['customer_id'],
            'showing_id' => $_POST['showing_id'],
            'spots_reserved' => $_POST['spots_reserved'],
            'status' => 'confirmed',
            'price_per_ticket' => 100, // Evt. dynamisk
            'total_price' => $_POST['spots_reserved'] * 100
        ];

        if ($this->model->createBooking($data)) {
            header("Location: " . BASE_URL . "index.php?page=admin_bookings");
            exit();
        } else {
            $this->pageLoader->renderErrorPage(500, "Kunne ikke oprette booking.");
        }
    }

    private function editBooking() {
        $orderNumber = $_POST['order_number'];
        $data = [
            'spots_reserved' => $_POST['spots_reserved'],
            'status' => $_POST['status']
        ];

        if ($this->model->updateBooking($data, ['order_number' => $orderNumber])) {
            header("Location: " . BASE_URL . "index.php?page=admin_bookings");
            exit();
        } else {
            $this->pageLoader->renderErrorPage(500, "Kunne ikke opdatere booking.");
        }
    }

    private function deleteBooking($orderNumber) {
        if ($this->model->deleteBooking(['order_number' => $orderNumber])) {
            header("Location: " . BASE_URL . "index.php?page=admin_bookings");
            exit();
        } else {
            $this->pageLoader->renderErrorPage(500, "Kunne ikke slette booking.");
        }
    }
}
