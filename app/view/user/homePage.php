
<body>
    <div class="container">
        <!-- Hero Section -->
        <section class="hero">
    <h2>Upcoming Movies</h2>
    <div class="hero-slider">
        <?php foreach ($upcomingMovies as $movie): ?>
            <div class="hero-slide">
                <img src="<?= htmlspecialchars($movie['poster']) ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
                <h3><?= htmlspecialchars($movie['title']) ?></h3>
                <p>Premiere: <?= htmlspecialchars($movie['release_date']) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</section>


        <!-- News Section -->
        <section class="news">
    <h2>News</h2>
    <?php foreach ($newsMovies as $movie): ?>
        <div class="news-item">
            <img src="<?= htmlspecialchars($movie['poster']) ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
            <h3><?= htmlspecialchars($movie['title']) ?></h3>
            <p>Udgivet: <?= htmlspecialchars($movie['release_date']) ?></p>
        </div>
    <?php endforeach; ?>
</section>

        <!-- Daily Showings -->
        <section class="daily-showings">
    <h2>Daily Showings</h2>
    <?php foreach ($dailyMovies as $movie): ?>
        <div class="movie-card">
            <img src="<?= htmlspecialchars($movie['poster']) ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
            <h3><?= htmlspecialchars($movie['title']) ?></h3>
            <p>Visningstid: <?= htmlspecialchars($movie['show_time']) ?></p>
        </div>
    <?php endforeach; ?>
</section>

        <section class="selected-genre-movies">
    <h2><?= htmlspecialchars($selectedGenre) ?> Movies</h2>
    <div class="genre-slider">
        <?php foreach ($genreMovies as $movie): ?>
            <div class="slide">
                <img src="<?= htmlspecialchars($movie['poster']) ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
                <h3><?= htmlspecialchars($movie['title']) ?></h3>
            </div>
        <?php endforeach; ?>
    </div>
</section>
        <!-- 10 Movies by Genre -->
        <section class="genre-movies">
    <h2>Movies by Genre</h2>
    <div class="genre-slider">
        <?php foreach ($randomGenreMovies as $movie): ?>
            <div class="slide">
                <img src="<?= htmlspecialchars($movie['poster']) ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
                <h3><?= htmlspecialchars($movie['title']) ?></h3>
                <p>Genre: <?= htmlspecialchars($movie['genre']) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</section>


        <footer>
        <div class="footer-container">
        <!-- Virksomhedsinfo -->
        <div class="company-info">
            <h3>Om Virksomheden</h3>
            <p><?= htmlspecialchars($settings['site_title'] ?? 'Drive-In Bio') ?></p>
            <p>Email: <a href="mailto:<?= htmlspecialchars($settings['contact_email'] ?? '') ?>"><?= htmlspecialchars($settings['contact_email'] ?? 'Ikke angivet') ?></a></p>
            <p>Åbningstider: <?= htmlspecialchars($settings['opening_hours'] ?? 'Ikke angivet') ?></p>
            <p><?= htmlspecialchars($settings['about_content'] ?? '') ?></p>
        </div>
    

        <!-- Kontaktformular -->
        <div class="contact-form">
            <h3>Kontakt Os</h3>
            <form action="/sendMail.php" method="POST">
                <label for="name">Navn:</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="message">Besked:</label>
                <textarea id="message" name="message" rows="4" required></textarea>

                <button type="submit">Send</button>
            </form>
        </div>
    </div>

    
</footer>

    </div>
</body>
</html>
