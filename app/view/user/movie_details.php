<div class="movie-details">
    <h1><?= htmlspecialchars($movie['title']) ?></h1> <!-- Filmens titel -->

    <div class="movie-info">
        <div class="movie-poster">
            <img src="<?= htmlspecialchars($movie['poster']) ?>" alt="<?= htmlspecialchars($movie['title']) ?> Poster" />
        </div>

        <div class="movie-description">
            <p><strong>Beskrivelse:</strong> <?= nl2br(htmlspecialchars($movie['description'])) ?></p>
            <p><strong>Genre:</strong> <?= htmlspecialchars($movie['genre']) ?></p>
            <p><strong>Skuespillere:</strong> <?= htmlspecialchars($movie['actors']) ?></p>
        </div>
    </div>

    <div class="movie-showtimes">
        <h2>Visningstider</h2>
        <?php if (!empty($showtimes)): ?>
            <ul>
                <?php foreach ($showtimes as $showtime): ?>
                    <li>
                        <?= htmlspecialchars($showtime['show_date']) ?> kl. <?= htmlspecialchars($showtime['show_time']) ?> 
                        (<?= htmlspecialchars($showtime['screen']) ?> skærm) - 
                        <?= htmlspecialchars($showtime['available_spots']) ?> pladser tilgængelige
                        <a href="book?showing_id=<?= htmlspecialchars($showtime['showing_id']) ?>" class="btn btn-primary">Book Billet</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Der er ingen kommende visninger på nuværende tidspunkt.</p>
        <?php endif; ?>
    </div>
</div>
