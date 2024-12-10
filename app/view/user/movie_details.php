<div class="movie-details">
    <h1><?= htmlspecialchars($movie['title']) ?></h1>
    <div class="movie-info">
        <img src="<?= htmlspecialchars($movie['poster']) ?>" alt="<?= htmlspecialchars($movie['title']) ?> Poster">
        <p><strong>Beskrivelse:</strong> <?= nl2br(htmlspecialchars($movie['description'])) ?></p>
        <p><strong>Genre:</strong> <?= htmlspecialchars($movie['genre']) ?></p>
        <p><strong>Skuespillere:</strong> <?= htmlspecialchars($movie['actors']) ?></p>
    </div>

    <div class="movie-showtimes">
    <h2>Visningstider</h2>
    <?php if (!empty($showtimes)): ?>
        <form action="booking.php" method="POST" id="bookingForm">
            <div class="showtime-cards">
                <?php foreach ($showtimes as $showtime): ?>
                    <div class="showtime-card">
                        <input type="radio" name="showtime_id" id="showtime_<?= htmlspecialchars($showtime['id']) ?>" value="<?= htmlspecialchars($showtime['id']) ?>" required>
                        <label for="showtime_<?= htmlspecialchars($showtime['id']) ?>">
                            <p><?= htmlspecialchars($showtime['show_date']) ?> kl. <?= htmlspecialchars($showtime['show_time']) ?></p>
                            <p><?= htmlspecialchars($showtime['screen']) ?> skærm</p>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>

            <label for="spots">Antal pladser:</label>
            <input type="number" name="spots" id="spots" min="1" max="10" value="1" required>

            <p>Total pris: <span id="totalPrice">0</span> DKK</p>

            <button type="submit">Book nu</button>
        </form>
    <?php else: ?>
        <p>Der er ingen kommende visninger på nuværende tidspunkt.</p>
    <?php endif; ?>
</div>

<script>
    const pricePerSpot = 50; // Standardpris pr. plads
    const spotsInput = document.getElementById('spots');
    const totalPriceElement = document.getElementById('totalPrice');

    function updatePrice() {
        const spots = parseInt(spotsInput.value, 10) || 0;
        totalPriceElement.textContent = spots * pricePerSpot;
    }

    spotsInput.addEventListener('input', updatePrice);
    updatePrice(); // Initial opdatering
</script>

<style>.showtime-cards {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin: 20px 0;
}

.showtime-card {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 10px;
    text-align: center;
    width: 150px;
    cursor: pointer;
    background-color: #f9f9f9;
    transition: transform 0.2s, box-shadow 0.2s;
}

.showtime-card:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.showtime-card input {
    display: none;
}

.showtime-card label {
    display: block;
    cursor: pointer;
}

.showtime-card input:checked + label {
    background-color: #007bff;
    color: white;
    border-color: #007bff;
}
</style>