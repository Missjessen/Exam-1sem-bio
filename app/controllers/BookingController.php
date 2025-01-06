<?php 

class BookingController {
    private $db;
    private $bookingModel;
    private $pageLoader;

    public function __construct($db) {
        $this->db = $db;
        $this->bookingModel = new BookingModel($db);
        $this->pageLoader = new PageLoader($db);
    }

     // Håndter forskellige handlinger
     public function handleAction($action) {
        try {
            switch ($action) {
                case 'handle_booking':
                    $this->handleBooking();
                    break;
                case 'confirm_booking':
                    $this->confirmBooking();
                    break;
                case 'cancel_booking':
                    $this->cancelBooking();
                    break;
                default:
                    throw new Exception("Ugyldig handling: $action");
            }
        } catch (Exception $e) {
            $this->pageLoader->renderErrorPage(500, "Fejl under håndtering af booking: " . $e->getMessage());
        }
    }

    // Håndter booking
    public function handleBooking() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception("Ugyldig anmodning. Kun POST er tilladt.");
            }
    
            $showingId = $_POST['showing_id'] ?? null;
            $spots = $_POST['spots'] ?? null;
    
            if (empty($showingId) || empty($spots)) {
                throw new Exception("Ugyldige data til booking.");
            }
    
            // Debug POST-data
            error_log("POST data: " . print_r($_POST, true));
    
            // Hent visningsdetaljer
            $showingDetails = $this->bookingModel->getShowingDetails($showingId);
            if (!$showingDetails) {
                throw new Exception("Visningen kunne ikke findes.");
            }
    
            // Beregn totalpris
            $totalPrice = $showingDetails['price_per_ticket'] * $spots;
    
            // Gem bookingdata i sessionen
            $_SESSION['pending_booking'] = [
                'showing_id' => $showingId,
                'spots' => $spots,
                'total_price' => $totalPrice,
                'movie_title' => $showingDetails['movie_title'],
                'show_date' => $showingDetails['show_date'],
                'show_time' => $showingDetails['show_time'],
                'price_per_ticket' => $showingDetails['price_per_ticket'],
            ];
    
            // Debug session-data
            error_log("Session data: " . print_r($_SESSION, true));
    
            // Redirect til booking oversigt
            header("Location: index.php?page=bookingSummary");
            exit;
            if (!headers_sent()) {
                header("Location: index.php?page=bookingSummary");
                exit;
            } else {
                error_log("Headers allerede sendt, omdirigering mislykkedes.");
                throw new Exception("Kunne ikke omdirigere til bookingsiden.");
            }
        } catch (Exception $e) {
            error_log("Fejl under håndtering af booking: " . $e->getMessage());
            $this->pageLoader->renderErrorPage(500, "Fejl under håndtering af booking: " . $e->getMessage());
        }
    }
    
    

    // Bekræft booking
    public function confirmBooking() {
        try {
            if (!isset($_SESSION['user_id'])) {
                $this->pageLoader->renderErrorPage(401, "Du skal være logget ind for at bekræfte en booking.");
                return;
            }
    
            $bookingData = $_SESSION['pending_booking'] ?? null;
    
            if (!$bookingData) {
                $this->pageLoader->renderErrorPage(400, "Ingen bookingdata fundet.");
                return;
            }
    
            $query = "
            INSERT INTO bookings (customer_id, showing_id, spots_reserved, price_per_ticket, total_price, status)
            VALUES (:customer_id, :showing_id, :spots_reserved, :price_per_ticket, :total_price, 'confirmed')
        ";
        
    
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':customer_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->bindParam(':showing_id', $bookingData['showing_id'], PDO::PARAM_INT);
            $stmt->bindParam(':spots_reserved', $bookingData['spots'], PDO::PARAM_INT);
            $stmt->bindParam(':price_per_ticket', $bookingData['price_per_ticket'], PDO::PARAM_STR);
            $stmt->bindParam(':total_price', $bookingData['total_price'], PDO::PARAM_STR);
    
            if ($stmt->execute()) {
                unset($_SESSION['pending_booking']); // Ryd midlertidige bookingdata
                $this->pageLoader->renderPage('booking_success', [], 'user');
            } else {
                $this->pageLoader->renderErrorPage(500, "Kunne ikke gennemføre bookingen. Prøv igen.");
            }
        } catch (PDOException $e) {
            $this->pageLoader->renderErrorPage(500, "Fejl under bekræftelse af booking: " . $e->getMessage());
        }
    }
    
    
    
    
       // Annuller booking
      public function cancelBooking() {
        try {
            unset($_SESSION['pending_booking']);
            header("Location: index.php?page=homePage");
            exit;
        } catch (Exception $e) {
            $this->pageLoader->renderErrorPage(500, "Fejl under annullering af booking: " . $e->getMessage());
        }
    }


    public function showReceipt() {
        try {
            if (!isset($_SESSION['user_id'])) {
                $this->pageLoader->renderErrorPage(401, "Du skal være logget ind for at se din kvittering.");
                return;
            }

            // Hent bookingdata fra session
            $bookingData = $_SESSION['pending_booking'] ?? null;

            if (!$bookingData) {
                $this->pageLoader->renderErrorPage(400, "Ingen bookingdata fundet.");
                return;
            }

            // Render kvitteringsside
            $this->pageLoader->renderPage('booking_receipt', $bookingData, 'user');
        } catch (Exception $e) {
            $this->pageLoader->renderErrorPage(500, "Fejl under indlæsning af kvitteringssiden: " . $e->getMessage());
        }
    }
    
    public function getTemporaryBooking() {
        return $_SESSION['temporary_booking'] ?? null;
    }
}