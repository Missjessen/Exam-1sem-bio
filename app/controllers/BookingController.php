<?php 

class BookingController {
    private $bookingModel;

    public function __construct($db) {
        $this->bookingModel = new BookingModel($db);
    }
    
    public function createBooking($customerId, $movieId, $showtimeId, $spotId, $spots, $screen, $rowType) {
        try {
            $price = $this->calculatePrice($screen, $rowType, $spots);
    
            $stmt = $this->db->prepare("
                INSERT INTO bookings (customer_id, movie_id, showtime_id, spot_id, price) 
                VALUES (:customer_id, :movie_id, :showtime_id, :spot_id, :price)
            ");
            $stmt->execute([
                ':customer_id' => $customerId,
                ':movie_id' => $movieId,
                ':showtime_id' => $showtimeId,
                ':spot_id' => $spotId,
                ':price' => $price
            ]);
    
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Fejl ved oprettelse af booking: " . $e->getMessage());
        }
    }
    

    public function calculatePrice($screen, $rowType, $spots) {
        try {
            $stmt = $this->db->prepare("
                SELECT price_per_spot 
                FROM parking_prices 
                WHERE screen = :screen AND row_type = :row_type
            ");
            $stmt->execute([':screen' => $screen, ':row_type' => $rowType]);
            $pricePerSpot = $stmt->fetchColumn();
    
            if (!$pricePerSpot) {
                throw new Exception("Pris for valgt skærm og række ikke fundet.");
            }
    
            return $pricePerSpot * $spots; // Totalpris
        } catch (PDOException $e) {
            throw new Exception("Fejl ved beregning af pris: " . $e->getMessage());
        }
    }
}
