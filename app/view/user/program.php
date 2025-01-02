<div class="program">
    <h1>Film Program</h1>
    <div class="movie-grid">
        <?php foreach ($movies as $movie): ?>
            <div class="movie-card">
            <a href="/movie_details/<?= htmlspecialchars($movie['slug'], ENT_QUOTES, 'UTF-8') ?>">
                    <img src="<?= htmlspecialchars($movie['poster'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($movie['title'], ENT_QUOTES, 'UTF-8') ?>">
                    <h3><?= htmlspecialchars($movie['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                    <p>Premiere: <?= htmlspecialchars($movie['premiere_date'], ENT_QUOTES, 'UTF-8') ?></p>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>


<style>
    .program {
    padding: 20px;
    background-color: #000;
    color: #f6f6f6;
}

.movie-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.movie-card {
    background-color: #1a1a1a;
    padding: 15px;
    border-radius: 5px;
    text-align: center;
    transition: transform 0.2s, box-shadow 0.2s;
}

.movie-card img {
    max-width: 100%;
    border-radius: 5px;
}

.movie-card h3 {
    margin-top: 10px;
    font-size: 18px;
    color: #f6f6f6;
}

.movie-card:hover {
    transform: scale(1.05);
    box-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
}

.movie-card a {
    text-decoration: none;
    color: inherit;
}

</style>