<div class="booking-receipt">
    <h1>Din Kvittering</h1>
    <?php if (isset($booking)): ?>
        <p><strong>Film:</strong> <?= htmlspecialchars($booking['movie_title']) ?></p>
        <p><strong>Dato:</strong> <?= htmlspecialchars($booking['show_date']) ?></p>
        <p><strong>Tid:</strong> <?= htmlspecialchars($booking['show_time']) ?></p>
        <p><strong>Antal pladser:</strong> <?= htmlspecialchars($booking['spots_reserved']) ?></p>
        <p><strong>Total Pris:</strong> <?= htmlspecialchars($booking['total_price']) ?> DKK</p>
        <p><strong>Ordrenummer:</strong> <?= htmlspecialchars($booking['order_number']) ?></p>
        <p><strong>Status:</strong> <?= htmlspecialchars($booking['status']) ?></p>
        <a href="index.php?page=profile" class="btn">Tilbage til Profil</a>
    <?php else: ?>
        <p>Fejl: Ingen data fundet for denne booking.</p>
    <?php endif; ?>
</div>


<style>
body {
    font-family: Arial, sans-serif;
    background-color: #000;
    color: #f6f6f6;
    margin: 0;
    padding: 0;
}

.booking-receipt {
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    background-color: #1a1a1a;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.4);
}

.booking-receipt h1 {
    font-size: 2rem;
    color: #f39c12;
    text-align: center;
    margin-bottom: 20px;
}

.booking-receipt p {
    font-size: 1rem;
    margin: 10px 0;
}

.booking-receipt strong {
    color: #f39c12;
}

.receipt-actions {
    margin-top: 20px;
    text-align: center;
}

.receipt-actions .btn {
    display: inline-block;
    margin: 10px;
    padding: 10px 20px;
    font-size: 1rem;
    font-weight: bold;
    text-transform: uppercase;
    color: #fff;
    text-decoration: none;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.receipt-actions .btn-primary {
    background-color: #f39c12;
}

.receipt-actions .btn-primary:hover {
    background-color: #e67e22;
}

.receipt-actions .btn-secondary {
    background-color: #555;
}

.receipt-actions .btn-secondary:hover {
    background-color: #777;
}
</style>
