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

    public function handleBooking() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->pageLoader->renderErrorPage(400, "Ugyldig anmodning til booking.");
                return;
            }

            // Håndter input fra formularen
            $showingId = $_POST['showing_id'] ?? null;
            $spots = $_POST['spots'] ?? null;

            if (empty($showingId) || empty($spots)) {
                $this->pageLoader->renderErrorPage(400, "Ugyldig bookingforespørgsel. Vælg venligst en visning og antal pladser.");
                return;
            }

            // Hent detaljer for visningen
            $showingDetails = $this->bookingModel->getShowingDetails($showingId);

            if (!$showingDetails) {
                $this->pageLoader->renderErrorPage(404, "Den valgte visning blev ikke fundet.");
                return;
            }

            // Beregn totalpris
            $totalPrice = $showingDetails['price_per_ticket'] * $spots;

            // Gem midlertidig booking i session
            $_SESSION['pending_booking'] = [
                'showing_id' => $showingId,
                'spots' => $spots,
                'total_price' => $totalPrice,
                'movie_title' => $showingDetails['movie_title'],
                'show_date' => $showingDetails['show_date'],
                'show_time' => $showingDetails['show_time'],
                'price_per_ticket' => $showingDetails['price_per_ticket']
            ];

            // Send brugeren til oversigtssiden
            $this->pageLoader->renderPage('bookingSummary', $_SESSION['pending_booking'], 'user');
        } catch (Exception $e) {
            $this->pageLoader->renderErrorPage(500, "Fejl under håndtering af booking: " . $e->getMessage());
        }
    }

    public function confirmBooking() {
        try {
            if (!isset($_SESSION['user_id'])) {
                $this->pageLoader->renderErrorPage(401, "Du skal være logget ind for at bekræfte en booking.");
                return;
            }

            // Hent data fra session
            $bookingData = $_SESSION['pending_booking'] ?? null;

            if (!$bookingData) {
                $this->pageLoader->renderErrorPage(400, "Ingen bookingdata fundet.");
                return;
            }

            // Opret booking
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