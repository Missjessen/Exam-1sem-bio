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
        // Kontrollér, om brugeren er logget ind
        if (!isset($_SESSION['user_id'])) {
            // Gem redirect URL i sessionen, så brugeren kan vende tilbage
            $_SESSION['redirect_url'] = "index.php?page=handle_booking";

            // Gem midlertidig bookingdata, hvis POST-data er tilgængelige
            if (!empty($_POST['showing_id']) && !empty($_POST['spots'])) {
                $_SESSION['pending_booking'] = [
                    'showing_id' => $_POST['showing_id'],
                    'spots' => $_POST['spots']
                ];
            }

            // Omdirigér til login-siden
            header("Location: index.php?page=login");
            exit;
        }

        // Hent data fra sessionen, hvis de findes
        $pendingBooking = $_SESSION['pending_booking'] ?? null;

        // Hvis der ikke er bookingdata, vis fejl
        if (empty($pendingBooking)) {
            $this->pageLoader->renderErrorPage(400, "Ingen bookingdata fundet.");
            return;
        }

        // Hent detaljer for visningen
        $showingDetails = $this->bookingModel->getShowingDetails($pendingBooking['showing_id']);

        if (!$showingDetails) {
            $this->pageLoader->renderErrorPage(404, "Den valgte visning blev ikke fundet.");
            return;
        }

        // Beregn den samlede pris
        $totalPrice = $showingDetails['price_per_ticket'] * $pendingBooking['spots'];

        // Opdater sessionen med alle bookingdetaljer
        $_SESSION['pending_booking'] = [
            'showing_id' => $pendingBooking['showing_id'],
            'spots' => $pendingBooking['spots'],
            'total_price' => $totalPrice,
            'movie_title' => $showingDetails['movie_title'],
            'show_date' => $showingDetails['show_date'],
            'show_time' => $showingDetails['show_time'],
            'price_per_ticket' => $showingDetails['price_per_ticket'],
        ];

        // Send til oversigtssiden
        $this->pageLoader->renderPage('bookingSummary', $_SESSION['pending_booking'], 'user');
    } catch (Exception $e) {
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
    
            // Send data til modellen for at oprette bookingen
            $isBooked = $this->bookingModel->createBooking($_SESSION['user_id'], $bookingData);
    
            if ($isBooked) {
                unset($_SESSION['pending_booking']);
                $this->pageLoader->renderPage('booking_success', [], 'user');
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