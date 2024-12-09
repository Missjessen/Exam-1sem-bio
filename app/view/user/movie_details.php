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

            <!-- Brug slug her -->
            <a href="book?movie=<?= urlencode($movie['slug']) ?>" class="btn btn-primary">Book Billet</a>
        </div>
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
            <p>Der er ingen kommende visninger på nuværende tidspunkt.</p>
        <?php endif; ?>
    </div>
</div>


<style>

body {
    font-family: Arial, sans-serif;
    background-color: #000; /* Mørk baggrund */
    color: #f6f6f6; /* Lys tekst */
    margin: 0;
    padding: 0;
}

/* Container til film detaljer */
.movie-details {
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    background-color: #1a1a1a; /* Mørk grå baggrund */
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.4);
}

/* Titel styling */
.movie-details h1 {
    font-size: 2rem;
    margin-bottom: 20px;
    color: #f39c12; /* Fremhæv titlen med en gylden farve */
    text-align: center;
}

/* Flex container for information */
.movie-info {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    align-items: flex-start;
}

/* Film plakat */
.movie-poster img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.6);
    border: 3px solid #f39c12;
    flex: 1 1 250px;
}

/* Beskrivelse */
.movie-description {
    flex: 2 1 500px;
}

.movie-description p {
    margin: 10px 0;
    line-height: 1.6;
}

.movie-description strong {
    color: #f39c12;
}

/* Knappen */
.btn.btn-primary {
    display: inline-block;
    margin-top: 20px;
    padding: 10px 20px;
    font-size: 1rem;
    font-weight: bold;
    text-transform: uppercase;
    text-align: center;
    color: #fff;
    background-color: #f39c12;
    border: none;
    border-radius: 5px;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

.btn.btn-primary:hover {
    background-color: #e67e22;
    cursor: pointer;
}

/* Visningstider */
.movie-showtimes {
    margin-top: 30px;
}

.movie-showtimes h2 {
    font-size: 1.5rem;
    margin-bottom: 15px;
    color: #f39c12;
    border-bottom: 2px solid #f39c12;
    display: inline-block;
    padding-bottom: 5px;
}

.movie-showtimes ul {
    list-style: none;
    padding: 0;
}

.movie-showtimes li {
    margin-bottom: 10px;
    padding: 10px;
    background-color: #2c2c2c;
    border-radius: 5px;
    border-left: 4px solid #f39c12;
}

/* Responsiv styling */
@media (max-width: 768px) {
    .movie-info {
        flex-direction: column;
        align-items: center;
    }

    .movie-poster img {
        max-width: 80%;
    }

    .movie-description {
        text-align: center;
    }
}
</style>