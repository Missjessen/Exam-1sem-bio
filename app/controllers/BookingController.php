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
    
            // Hent visningsdetaljer
            $showingDetails = $this->bookingModel->getShowingDetails($showingId);
            if (!$showingDetails) {
                error_log("Ingen visningsdetaljer fundet for ID: $showingId");
                throw new Exception("Visningen kunne ikke findes.");
            }
    
            // Beregn totalpris
            $totalPrice = $showingDetails['price_per_ticket'] * $spots;
    
            // Opdater sessionen
            $_SESSION['pending_booking'] = [
                'showing_id' => $showingId,
                'spots' => $spots,
                'total_price' => $totalPrice,
                'movie_title' => $showingDetails['movie_title'],
                'show_date' => $showingDetails['show_date'],
                'show_time' => $showingDetails['show_time'],
                'price_per_ticket' => $showingDetails['price_per_ticket'],
            ];
    
            // Debugging
            error_log("Booking data gemt i session: " . print_r($_SESSION['pending_booking'], true));
    
            // Redirect til booking oversigt
            header("Location: index.php?page=bookingSummary");
            exit;
        } catch (Exception $e) {
            error_log("Fejl under håndtering af booking: " . $e->getMessage());
            $this->pageLoader->renderErrorPage(500, "Fejl under håndtering af booking: " . $e->getMessage());
        }
    }
    
    

    // Bekræft booking
    public function confirmBooking() {
        try {
            if (!isset($_SESSION['user_id'])) {
                throw new Exception("Du skal være logget ind for at bekræfte en booking.");
            }

            $bookingData = $_SESSION['pending_booking'] ?? null;

            if (!$bookingData) {
                throw new Exception("Ingen bookingdata fundet.");
            }

            // Gem booking i databasen
            $orderNumber = $this->bookingModel->createBooking($_SESSION['user_id'], $bookingData);

            // Gem kvitteringsdata i session
            $_SESSION['last_booking'] = [
                'order_number' => $orderNumber,
                'movie_title' => $bookingData['movie_title'],
                'show_date' => $bookingData['show_date'],
                'show_time' => $bookingData['show_time'],
                'spots' => $bookingData['spots'],
                'total_price' => $bookingData['total_price'],
            ];

            unset($_SESSION['pending_booking']); // Ryd midlertidige bookingdata
            header("Location: index.php?page=booking_success&order_number=" . $orderNumber);
            exit;
        } catch (Exception $e) {
            error_log("Fejl under bekræftelse af booking: " . $e->getMessage());
            $this->pageLoader->renderErrorPage(500, $e->getMessage());
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


    public function showReceipt($orderNumber) {
        try {
            if (!isset($_SESSION['user_id'])) {
                throw new Exception("Du skal være logget ind for at se din kvittering.");
            }

            $bookingData = $this->bookingModel->getBookingByOrderNumber($orderNumber, $_SESSION['user_id']);

            if (!$bookingData) {
                throw new Exception("Ingen kvittering fundet for ordrenummer: $orderNumber.");
            }

            $this->pageLoader->renderPage('booking_receipt', $bookingData, 'user');
        } catch (Exception $e) {
            $this->pageLoader->renderErrorPage(500, $e->getMessage());
        }
    }

    public function bookingSummary() {
        try {
            // Tjek sessiondata for 'pending_booking'
            $booking = $_SESSION['pending_booking'] ?? null;
    
            // Hvis ingen data i sessionen, hent fra databasen
            if (!$booking) {
                error_log("Ingen bookingdata fundet i session. Henter fra databasen...");
                $booking = $this->bookingModel->getLatestBookingByUser($_SESSION['user_id']);
            }
    
            // Hvis stadig ingen bookingdata
            if (!$booking) {
                $this->pageLoader->renderErrorPage(400, "Ingen booking fundet. Start en ny booking.");
                return;
            }
    
            // Render booking-oversigten
            $this->pageLoader->renderPage('bookingSummary', ['booking' => $booking], 'user');
        } catch (Exception $e) {
            error_log("Fejl under indlæsning af booking oversigt: " . $e->getMessage());
            $this->pageLoader->renderErrorPage(500, "Fejl under indlæsning af booking oversigt: " . $e->getMessage());
        }
    }

    public function getBookingByOrderNumber($orderNumber, $userId) {
        try {
            $query = "
                SELECT 
                    b.order_number, b.total_price, b.spots_reserved, 
                    s.show_date, s.show_time, m.title AS movie_title
                FROM bookings b
                JOIN showings s ON b.showing_id = s.id
                JOIN movies m ON s.movie_id = m.id
                WHERE b.order_number = :order_number AND b.customer_id = :customer_id
            ";
    
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':order_number', $orderNumber, PDO::PARAM_INT);
            $stmt->bindParam(':customer_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
    
            $booking = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if (!$booking) {
                throw new Exception("Ingen kvittering fundet for ordrenummer: $orderNumber.");
            }
    
            return $booking;
        } catch (Exception $e) {
            throw new Exception("Fejl under indlæsning af bookingdata: " . $e->getMessage());
        }
    }
    
   /*  function generateShortUUID() {
        // Generer en 16-byte UUID
        $uuid = bin2hex(random_bytes(16));
        // Konverter UUID til Base64
        $base64 = base64_encode(hex2bin($uuid));
        // Fjern '=' padding og gør den URL-venlig
        $base64 = rtrim($base64, '=');
        return str_replace(['+', '/'], ['-', '_'], $base64);
    }
     */
    
    
    private function getBookingDataFromSession() {
        return $_SESSION['pending_booking'] ?? null;
    }
    
    private function clearPendingBooking() {
        unset($_SESSION['pending_booking']);
    }
 
    
}