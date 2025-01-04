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
        // Håndter input fra movie details
        $showingId = $_POST['showing_id'] ?? null;
        $spots = $_POST['spots'] ?? null;

        if (empty($showingId) || empty($spots)) {
            $this->pageLoader->renderErrorPage(400, "Ugyldig bookingforespørgsel.");
            return;
        }

        // Hent detaljer for visningen
        $showingDetails = $this->bookingModel->getShowingDetails($showingId);

        if (!$showingDetails) {
            $this->pageLoader->renderErrorPage(404, "Den valgte visning blev ikke fundet.");
            return;
        }

        // Beregn den samlede pris
        $totalPrice = $showingDetails['price_per_ticket'] * $spots;

        // Gem bookingdata midlertidigt i session
        $_SESSION['pending_booking'] = [
            'showing_id' => $showingId,
            'spots' => $spots,
            'total_price' => $totalPrice,
            'movie_title' => $showingDetails['movie_title'],
            'show_date' => $showingDetails['show_date'],
            'show_time' => $showingDetails['show_time'],
        ];

        // Send til oversigtsside
        $this->pageLoader->renderPage('bookingSummary', $_SESSION['pending_booking'], 'user');
    }

    public function confirmBooking() {
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
            unset($_SESSION['pending_booking']); // Fjern midlertidige bookingdata
            $this->pageLoader->renderPage('booking_success', [], 'user');
        } else {
            $this->pageLoader->renderErrorPage(500, "Kunne ikke gennemføre bookingen. Prøv igen.");
        }
    }

    public function showReceipt() {
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
    
        // Send data til kvitteringsvisning
        $this->pageLoader->renderPage('booking_Receipt', $bookingData, 'user');
    }
    

    public function cancelBooking() {
        unset($_SESSION['pending_booking']);
        header("Location: index.php");
        exit;
    }
}
