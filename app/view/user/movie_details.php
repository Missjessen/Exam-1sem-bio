<div class="movie-details">
    <!-- Film detaljer -->
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
                    <form method="POST" action="booking_process.php">
                        <input type="hidden" name="showing_id" value="<?= htmlspecialchars($showing['showing_id']) ?>">
                        <button type="submit">Book denne visning</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Der er ingen planlagte visninger for denne film i øjeblikket.</p>
    <?php endif; ?>

    <!-- Bookingformular -->
    <h2>Bookingformular</h2>
    <form method="POST" action="booking_process.php">
        <label for="showtime">Vælg spilletid:</label>
        <select name="showtime_id" id="showtime" required>
            <?php if (!empty($showtimes)): ?>
                <?php foreach ($showtimes as $showtime): ?>
                    <option value="<?= htmlspecialchars($showtime['showing_id']) ?>">
                        <?= htmlspecialchars($showtime['show_date']) ?> kl. <?= htmlspecialchars($showtime['show_time']) ?>
                        (Skærm: <?= htmlspecialchars($showtime['screen']) ?>)
                    </option>
                <?php endforeach; ?>
            <?php else: ?>
                <option disabled>Ingen visningstider tilgængelige</option>
            <?php endif; ?>
        </select>

        <label for="spots">Antal pladser:</label>
        <input type="number" id="spots" name="spots" min="1" max="<?= htmlspecialchars($showtimes[0]['available_spots'] ?? 10) ?>" required>

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
    totalPriceElement.textContent = '0 DKK';
</script>

<style>
    form {
        max-width: 400px;
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 10px;
        background-color: #f9f9f9;
    }

    label {
        font-weight: bold;
        display: block;
        margin: 10px 0 5px;
    }

    select, input[type="number"] {
        width: 100%;
        padding: 8px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    button {
        display: block;
        width: 100%;
        padding: 10px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
    }

    button:hover {
        background-color: #0056b3;
    }

    #totalPrice {
        font-weight: bold;
        color: green;
    }
</style>
