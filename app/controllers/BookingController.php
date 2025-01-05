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
            // Kontrollér, om brugeren er logget ind
            if (!isset($_SESSION['user_id'])) {
                // Hvis brugeren ikke er logget ind, gem redirect URL og bookingdata midlertidigt
                $_SESSION['redirect_url'] = "index.php?page=confirm_booking";
                $_SESSION['pending_booking'] = $_SESSION['pending_booking'] ?? null;
    
                // Omdiriger til login-siden
                header("Location: index.php?page=login");
                exit;
            }
    
            // Hent bruger-ID fra sessionen
            $customerId = $_SESSION['user_id'];
    
            // Hent bookingdata fra sessionen
            $bookingData = $_SESSION['pending_booking'] ?? null;
    
            if (!$bookingData) {
                $this->pageLoader->renderErrorPage(400, "Ingen bookingdata fundet.");
                return;
            }
    
            // Gem bookingdata i databasen
            $isBooked = $this->bookingModel->createBooking($customerId, $bookingData);
    
            if ($isBooked) {
                // Fjern midlertidige bookingdata fra sessionen
                unset($_SESSION['pending_booking']);
    
                // Vis en succes-side
                $this->pageLoader->renderPage('booking_success', $bookingData, 'user');
            } else {
                $this->pageLoader->renderErrorPage(500, "Kunne ikke gennemføre bookingen. Prøv igen.");
            }
        } catch (Exception $e) {
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