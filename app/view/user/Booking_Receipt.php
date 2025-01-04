<div class="booking-receipt">
    <h1>Din Booking Kvittering</h1>

    <p><strong>Film:</strong> <?= htmlspecialchars($movie_title) ?></p>
    <p><strong>Visningsdato:</strong> <?= htmlspecialchars($show_date) ?></p>
    <p><strong>Visningstid:</strong> <?= htmlspecialchars($show_time) ?></p>
    <p><strong>Antal pladser:</strong> <?= htmlspecialchars($spots_reserved) ?></p>
    <p><strong>Pris pr. billet:</strong> <?= htmlspecialchars($price_per_ticket) ?> DKK</p>
    <p><strong>Samlet pris:</strong> <?= htmlspecialchars($total_price) ?> DKK</p>

    <p>Tak, fordi du har booket hos Cruise Nights Cinema. Vi glæder os til at se dig!</p>

    <div class="receipt-actions">
        <a href="?page=home" class="btn btn-primary">Tilbage til Forsiden</a>
        <button onclick="window.print();" class="btn btn-secondary">Print Kvittering</button>
    </div>
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
