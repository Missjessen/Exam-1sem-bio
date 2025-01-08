<div class="booking-success">
    <h1>Din Booking Oversigt</h1>
    <?php if (isset($booking)): ?>
        <p><strong>Film:</strong> <?= htmlspecialchars($booking['movie_title']) ?></p>
        <p><strong>Dato:</strong> <?= htmlspecialchars($booking['show_date']) ?></p>
        <p><strong>Tid:</strong> <?= htmlspecialchars($booking['show_time']) ?></p>
        <p><strong>Antal pladser:</strong> <?= htmlspecialchars($booking['spots_reserved']) ?></p>
        <p><strong>Total Pris:</strong> <?= htmlspecialchars($booking['total_price']) ?> DKK</p>
        <p><strong>Ordrenummer:</strong> <?= htmlspecialchars($booking['order_number']) ?></p>
        <p>Tak for din booking. Vi glæder os til at se dig!</p>
        <a href="index.php?page=homePage" class="btn">Tilbage til forsiden</a>
    <?php else: ?>
        <p>Ingen bookingdata fundet.</p>
        <a href="index.php?page=homePage" class="btn">Tilbage til forsiden</a>
    <?php endif; ?>
</div>



<style>
    .booking-success {
        text-align: center;
        padding: 20px;
        max-width: 600px;
        margin: 50px auto;
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-family: Arial, sans-serif;
    }

    .booking-success h1 {
        color: #28a745;
    }

    .booking-success p {
        font-size: 1.2rem;
        margin: 10px 0;
    }

    .btn {
        display: inline-block;
        margin-top: 20px;
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 5px;
    }

    .btn:hover {
        background-color: #0056b3;
    }
</style>
