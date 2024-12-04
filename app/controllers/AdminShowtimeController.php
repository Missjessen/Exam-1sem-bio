<?php
require_once BASE_PATH . 'init.php';

class AdminShowtimeController {
    private $model;

    public function __construct($model) {
        $this->model = $model;
    }

    public function createShowtime($movieId, $date, $time) {
        // Validér input
        if (empty($movieId) || empty($date) || empty($time)) {
            return ['error' => 'Alle felter skal udfyldes'];
        }

        // Opret showtime
        $result = $this->model->addShowtime($movieId, $date, $time);

        if ($result) {
            return ['success' => 'Showtime oprettet'];
        } else {
            return ['error' => 'Kunne ikke oprette showtime'];
        }
    }
}
