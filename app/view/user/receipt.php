<div>
    <h1>Booking Kvittering</h1>
    <?php if ($success === 'true' && !empty($booking)): ?>
        <p><strong>Kunde:</strong> <?= htmlspecialchars($booking['customer_name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($booking['customer_email']) ?></p>
        <p><strong>Film:</strong> <?= htmlspecialchars($booking['movie_title']) ?></p>
        <p><strong>Visning:</strong> <?= htmlspecialchars($booking['show_date']) ?> kl. <?= htmlspecialchars($booking['show_time']) ?></p>
        <p><strong>Skærm:</strong> <?= htmlspecialchars($booking['screen']) ?></p>
        <p><strong>Total pris:</strong> <?= htmlspecialchars($booking['price']) ?> DKK</p>
        <p><strong>Bookingdato:</strong> <?= htmlspecialchars($booking['booking_date']) ?></p>
    <?php elseif ($success === 'false'): ?>
        <p>Booking mislykkedes. Prøv igen.</p>
    <?php else: ?>
        <p>Kunne ikke finde bookingoplysninger.</p>
    <?php endif; ?>
</div>
