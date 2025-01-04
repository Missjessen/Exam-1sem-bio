<?php if (!isset($_SESSION)) { session_start(); } ?>

<div class="booking-summary">
    <h1>Booking Oversigt</h1>

    <?php if (!empty($_SESSION['pending_booking'])): ?>
        <?php $booking = $_SESSION['pending_booking']; ?>

        <p><strong>Film:</strong> <?= htmlspecialchars($booking['movie_title'], ENT_QUOTES, 'UTF-8') ?></p>
        <p><strong>Dato:</strong> <?= htmlspecialchars($booking['show_date'], ENT_QUOTES, 'UTF-8') ?></p>
        <p><strong>Tid:</strong> <?= htmlspecialchars($booking['show_time'], ENT_QUOTES, 'UTF-8') ?></p>
        <p><strong>Antal pladser:</strong> <?= htmlspecialchars($booking['spots'], ENT_QUOTES, 'UTF-8') ?></p>
        <p><strong>Pris per billet:</strong> <?= htmlspecialchars($booking['price_per_ticket'], ENT_QUOTES, 'UTF-8') ?> DKK</p>
        <p><strong>Samlet pris:</strong> <?= htmlspecialchars($booking['total_price'], ENT_QUOTES, 'UTF-8') ?> DKK</p>

        <!-- Handlinger -->
        <form method="POST" action="index.php?page=confirm_booking">
            <button type="submit">Bekræft Booking</button>
        </form>

        <form method="POST" action="index.php?page=cancel_booking">
            <button type="submit">Annuller Booking</button>
        </form>

    <?php else: ?>
        <p>Ingen aktiv booking fundet.</p>
    <?php endif; ?>
</div>
