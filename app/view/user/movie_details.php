<div class="movie-details">
    <h1><?= htmlspecialchars($movie['title']) ?></h1>
    <p><strong>Beskrivelse:</strong> <?= nl2br(htmlspecialchars($movie['description'] ?? 'Ingen beskrivelse tilgængelig')) ?></p>
    <p><strong>Genre:</strong> <?= htmlspecialchars($movie['genre'] ?? 'Ikke angivet') ?></p>
    <p><strong>Skuespillere:</strong> <?= htmlspecialchars($movie['actors'] ?? 'Ikke angivet') ?></p>

    <!-- Visningstider -->
    <h2>Visningstider</h2>
    <?php if (!empty($showtimes)): ?>
        <ul>
            <?php foreach ($showtimes as $showing): ?>
                <li>
                    <?= htmlspecialchars($showing['show_date']) ?> kl. <?= htmlspecialchars($showing['show_time']) ?> 
                    (Skærm: <?= htmlspecialchars($showing['screen']) ?>, Ledige pladser: <?= htmlspecialchars($showing['available_spots']) ?>)
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Der er ingen planlagte visninger for denne film i øjeblikket.</p>
    <?php endif; ?>

    <!-- Bookingformular -->
    <h2>Bookingformular</h2>
    <form method="POST" action="/?page=bookingAndReceipt&slug=<?= urlencode($slug) ?>">

        <label for="showtime">Vælg spilletid:</label>
        <select name="showing_id" id="showtime" required>
            <?php foreach ($showtimes as $showing): ?>
                <option value="<?= htmlspecialchars($showing['showing_id']) ?>">
                    <?= htmlspecialchars($showing['show_date']) ?> kl. <?= htmlspecialchars($showing['show_time']) ?>
                    (Skærm: <?= htmlspecialchars($showing['screen'] ?? 'Ukendt skærm') ?>, Ledige pladser: <?= htmlspecialchars($showing['available_spots']) ?>)
                </option>
            <?php endforeach; ?>
        </select>

        <label for="spots">Antal pladser:</label>
        <input type="number" id="spots" name="spots" min="1" max="10" required>

        <h3>Total pris:</h3>
        <p id="totalPrice">0 DKK</p>

        <button type="submit">Book nu</button>
    </form>
</div>

<script>
    // Dynamisk prisberegning
    const spotsInput = document.getElementById('spots');
    const totalPriceElement = document.getElementById('totalPrice');
    const pricePerSpot = 50; // Justér prisen per plads efter behov

    spotsInput.addEventListener('input', () => {
        const spots = parseInt(spotsInput.value, 10) || 0;
        totalPriceElement.textContent = (spots * pricePerSpot) + ' DKK';
    });

    // Initial opdatering af pris
    const initialSpots = parseInt(spotsInput.value, 10) || 0;
    totalPriceElement.textContent = (initialSpots * pricePerSpot) + ' DKK';
</script>
