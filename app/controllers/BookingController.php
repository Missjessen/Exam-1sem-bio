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
                $this->pageLoader->renderErrorPage(401, "Du skal være logget ind for at bekræfte en booking.");
                return;
            }
    
            $bookingData = $_SESSION['pending_booking'] ?? null;
    
            if (!$bookingData) {
                $this->pageLoader->renderErrorPage(400, "Ingen bookingdata fundet.");
                return;
            }
    
            // Generer et unikt order_number
            $orderNumber = 'ORDER-' . strtoupper(uniqid());
    
            $query = "
                INSERT INTO bookings (customer_id, showing_id, spots_reserved, price_per_ticket, total_price, status, order_number)
                VALUES (:customer_id, :showing_id, :spots_reserved, :price_per_ticket, :total_price, 'confirmed', :order_number)
            ";
    
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':customer_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->bindParam(':showing_id', $bookingData['showing_id'], PDO::PARAM_INT);
            $stmt->bindParam(':spots_reserved', $bookingData['spots'], PDO::PARAM_INT);
            $stmt->bindParam(':price_per_ticket', $bookingData['price_per_ticket'], PDO::PARAM_STR);
            $stmt->bindParam(':total_price', $bookingData['total_price'], PDO::PARAM_STR);
            $stmt->bindParam(':order_number', $orderNumber, PDO::PARAM_STR);
    
            if ($stmt->execute()) {
                unset($_SESSION['pending_booking']); // Ryd midlertidige bookingdata
                $this->pageLoader->renderPage('booking_success', ['order_number' => $orderNumber], 'user');
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


    public function showReceipt($orderNumber) {
        try {
            if (!isset($_SESSION['user_id'])) {
                $this->pageLoader->renderErrorPage(401, "Du skal være logget ind for at se din kvittering.");
                return;
            }
    
            // Hent bookingdata fra databasen
            $query = "
                SELECT 
                    b.order_number, b.total_price, b.status, b.spots_reserved, 
                    s.show_date, s.show_time, m.title AS movie_title
                FROM bookings b
                JOIN showings s ON b.showing_id = s.id
                JOIN movies m ON s.movie_id = m.id
                WHERE b.order_number = :order_number AND b.customer_id = :customer_id
            ";
    
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':order_number', $orderNumber, PDO::PARAM_STR);
            $stmt->bindParam(':customer_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->execute();
    
            $bookingData = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if (!$bookingData) {
                $this->pageLoader->renderErrorPage(404, "Ingen kvittering fundet for ordrenummer: $orderNumber.");
                return;
            }
    
            // Render kvitteringsside
            $this->pageLoader->renderPage('booking_receipt', $bookingData, 'user');
        } catch (PDOException $e) {
            $this->pageLoader->renderErrorPage(500, "Fejl under indlæsning af kvitteringssiden: " . $e->getMessage());
        }
    }
    
}