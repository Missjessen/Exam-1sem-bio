<div class="booking-summary">
    <h1>Booking Oversigt</h1>
    <p><strong>Film:</strong> <?= htmlspecialchars($movie_title) ?></p>
    <p><strong>Dato:</strong> <?= htmlspecialchars($show_date) ?></p>
    <p><strong>Tid:</strong> <?= htmlspecialchars($show_time) ?></p>
    <p><strong>Antal pladser:</strong> <?= htmlspecialchars($spots) ?></p>
    <p><strong>Samlet pris:</strong> <?= htmlspecialchars($total_price) ?> DKK</p>

    <form method="post" action="?page=confirm_booking">
        <button type="submit">Bekræft Booking</button>
    </form>

    <form method="post" action="?page=cancel_booking">
        <button type="submit">Annuller Booking</button>
    </form>
</div>
