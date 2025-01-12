<?php

class AdminBookingController {
    private $model;

    public function __construct($db) {
        $this->model = new AdminBookingModel($db);
    }

    // Hent alle bookinger
    public function listBookings() {
        return $this->model->getAllBookings();
    }

    // Hent en specifik booking
    public function getBooking($orderNumber) {
        return $this->model->getBookingByOrderNumber($orderNumber);
    }

    // Opdater en booking
    public function updateBooking($orderNumber, $data) {
        return $this->model->updateBooking($orderNumber, $data);
    }
    

    // Slet en booking
    public function deleteBooking($orderNumber) {
        return $this->model->deleteBooking($orderNumber);
    }

public function getBookingDetails($orderNumber) {
    return $this->adminBookingModel->getBookingByOrderNumber($orderNumber);
}

public function updateBooking($orderNumber, $data) {
    return $this->adminBookingModel->updateBooking($orderNumber, $data);
}
}