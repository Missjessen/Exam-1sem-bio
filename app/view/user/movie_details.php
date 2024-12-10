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
            <form action="booking.php" method="post">
                <input type="hidden" name="movie_id" value="<?= htmlspecialchars($movie['id']) ?>">
                <label for="showtime">Vælg visning:</label>
                <select name="showtime_id" id="showtime" required>
                    <?php foreach ($showtimes as $showtime): ?>
                        <option value="<?= htmlspecialchars($showtime['id']) ?>">
                            <?= htmlspecialchars($showtime['show_date']) ?> kl. <?= htmlspecialchars($showtime['show_time']) ?> (<?= htmlspecialchars($showtime['screen']) ?> skærm)
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Foretag booking</button>
            </form>
        <?php else: ?>
            <p>Der er ingen visningstider tilgængelige.</p>
        <?php endif; ?>
    </div>
</div>
