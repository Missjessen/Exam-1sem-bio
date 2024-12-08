<div class="program">
    <h1>Film Program</h1>
    <div class="movie-grid">
        <?php foreach ($movies as $movie): ?>
            <div class="movie-card">
                <a href="?page=movie_details&slug=<?= htmlspecialchars($movie['slug'], ENT_QUOTES, 'UTF-8') ?>">
                    <img src="<?= htmlspecialchars($movie['poster'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($movie['title'], ENT_QUOTES, 'UTF-8') ?>">
                    <h3><?= htmlspecialchars($movie['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                    <p>Premiere: <?= htmlspecialchars($movie['premiere_date'], ENT_QUOTES, 'UTF-8') ?></p>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>
