<?php
require_once BASE_PATH . '/init.php';

class ReviewController {
    private $model;

    public function __construct($db) {
        $this->model = new ReviewModel($db); // Sørg for, at $db sendes korrekt
    }

    public function addReview($data) {
        return $this->model->createReview($data);
    }

    public function getReviews($movie_uuid) {
        return $this->model->getReviewsByMovie($movie_uuid);
    }
}
