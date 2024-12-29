<?php
require_once '/init.php';

if (!isset($_GET['booking_id'])) {
    echo 'Booking ID mangler.';
    exit;
}

$bookingId = $_GET['booking_id'];
$model = new AdminBookingModel($db);
$booking = $model->getBookingById($bookingId);

if (!$booking) {
    echo 'Booking ikke fundet.';
    exit;
}

?>

<h2>Faktura</h2>
<p><strong>Kunde:</strong> <?= htmlspecialchars($booking['customer_name']); ?></p>
<p><strong>Film:</strong> <?= htmlspecialchars($booking['movie_title']); ?></p>
<p><strong>Parkeringsplads:</strong> <?= ucfirst(htmlspecialchars($booking['screen'])); ?></p>
<p><strong>Tidspunkt:</strong> <?= htmlspecialchars($booking['booking_time']); ?></p>
<p><strong>Antal parkerede pladser:</strong> <?= htmlspecialchars($booking['spots_booked']); ?></p>
<p><strong>Total pris:</strong> <?= htmlspecialchars($booking['total_price']); ?> DKK</p>
