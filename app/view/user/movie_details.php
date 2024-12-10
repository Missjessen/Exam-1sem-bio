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
            <label for="showtime">Vælg visning:</label>
            <select name="showtime_id" id="showtime" required>
                <?php foreach ($showtimes as $showtime): ?>
                    <option value="<?= htmlspecialchars($showtime['id']) ?>">
                        <?= htmlspecialchars($showtime['show_date']) ?> kl. <?= htmlspecialchars($showtime['show_time']) ?> (<?= htmlspecialchars($showtime['screen']) ?> skærm)
                    </option>
                <?php endforeach; ?>
            </select>

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
