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
            // Kontrollér, om POST-data er sendt korrekt
            $showingId = $_POST['showing_id'] ?? null;
            $spots = $_POST['spots'] ?? null;
    
            if (empty($showingId) || empty($spots)) {
                throw new Exception("Ugyldig bookingforespørgsel. Mangler showing_id eller spots.");
            }
    
            // Hent detaljer for visningen
            $showingDetails = $this->bookingModel->getShowingDetails($showingId);
    
            if (!$showingDetails) {
                throw new Exception("Den valgte visning blev ikke fundet.");
            }
    
            // Beregn totalpris
            $totalPrice = $showingDetails['price_per_ticket'] * $spots;
    
            // Gem data i sessionen
            $_SESSION['pending_booking'] = [
                'showing_id' => $showingId,              // ID for den valgte visning
                'spots' => $spots,                      // Antal reserverede pladser
                'total_price' => $totalPrice,           // Samlet pris for bookingen
                'movie_title' => $showingDetails['movie_title'],  // Filmtitel
                'show_date' => $showingDetails['show_date'],      // Visningsdato
                'show_time' => $showingDetails['show_time'],      // Visningstidspunkt
                'price_per_ticket' => $showingDetails['price_per_ticket'],  // Pris pr. billet
            ];
            
    
            // Debug: Log session-data
            error_log("Session data: " . print_r($_SESSION['pending_booking'], true));
    
            // Send til oversigtssiden
            $this->pageLoader->renderPage('bookingSummary', $_SESSION['pending_booking'], 'user');
        } catch (Exception $e) {
            $this->pageLoader->renderErrorPage(400, $e->getMessage());
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