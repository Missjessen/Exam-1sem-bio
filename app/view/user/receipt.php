<?php
$success = $_GET['booking_success'] ?? 'false'; // Tjek om booking_success er sat
?>
<div>
    <h1>Booking Kvittering</h1>
    <?php
    if ($_GET['booking_success'] === 'true') {
    $bookingId = $_GET['booking_id'];
    $stmt = $db->prepare("SELECT * FROM bookings WHERE booking_id = :booking_id");
    $stmt->execute([':booking_id' => $bookingId]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);

    echo "<h1>Booking Kvittering</h1>";
    echo "<p>Film: {$booking['movie_id']}</p>";
    echo "<p>Visning: {$booking['showtime_id']}</p>";
    echo "<p>Antal pladser: {$booking['spot_id']}</p>";
    echo "<p>Total pris: {$booking['price']} DKK</p>";
} else {
    echo "<p>Booking mislykkedes. Prøv igen.</p>";
}
?>
</div>

