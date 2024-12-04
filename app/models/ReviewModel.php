<?php
require_once BASE_PATH . 'init.php';
class ReviewModel {
    private $db;

    public function __construct($db) {
        $this->db = $db; // Sørg for, at $db bliver tildelt korrekt
    }

    public function createReview($data) {
        $stmt = $this->db->prepare("INSERT INTO reviews (movie_id, customer_id, rating, comment) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$data['movie_id'], $data['customer_id'], $data['rating'], $data['comment']]);
    }

    public function getReviewsByMovie($movie_uuid) {
        if (!$movie_uuid) {
            throw new Exception("Ugyldigt film-ID.");
        }
        
        $stmt = $this->db->prepare("SELECT rating, comment, created_at FROM reviews WHERE movie_id = ? ORDER BY created_at DESC");
        $stmt->execute([$movie_uuid]);
        return $stmt->fetchAll();
    }
}
