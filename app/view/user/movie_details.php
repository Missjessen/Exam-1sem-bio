<?php if (!empty($movie)): ?>
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
                <ul>
                    <?php foreach ($showtimes as $showtime): ?>
                        <li><?= htmlspecialchars($showtime['show_date']) ?> kl. <?= htmlspecialchars($showtime['show_time']) ?> (<?= htmlspecialchars($showtime['screen']) ?> skærm)</li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Der er ingen visninger for denne film.</p>
            <?php endif; ?>
        </div>
    </div>
<?php else: ?>
    <p>Ingen data tilgængelig for denne film.</p>
<?php endif; ?>
