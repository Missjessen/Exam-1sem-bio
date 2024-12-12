<body>
     <!-- Hero Image Section -->
     <header class="hero-image">
        <div class="hero-text">
            
            <p>Experience the best movies from the comfort of your car.</p>
        
        </div>
    </header>


    <section class="hero">
    <h2>Upcoming Movies</h2>
        <div class="hero-grid">
            <?php foreach ($upcomingMovies as $movie): ?>
                <div class="hero-slide">
                <a href="?page=movie_details&slug=<?= htmlspecialchars($movie['slug'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    <img src="<?= htmlspecialchars($movie['poster'] ?? '', ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($movie['title'] ?? 'Unknown Movie', ENT_QUOTES, 'UTF-8') ?>">
                    <h3><?= htmlspecialchars($movie['title'] ?? 'Unknown Title', ENT_QUOTES, 'UTF-8') ?></h3>
                    <p>Premiere: <?= htmlspecialchars($movie['release_date'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></p>
                </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

        <!-- News Section -->
        <section class="news">
        <h2>Latest Releases</h2>
        <div class="news-grid">
            <?php foreach ($newsMovies as $movie): ?>
                <div class="news-item">
                <a href="?page=movie_details&slug=<?= htmlspecialchars($movie['slug'] ?? '', ENT_QUOTES, 'UTF-8') ?>">


                        <img src="<?= htmlspecialchars($movie['poster'] ?? '', ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($movie['title'] ?? 'Unknown Movie', ENT_QUOTES, 'UTF-8') ?>">
                        <h3><?= htmlspecialchars($movie['title'] ?? 'Unknown Title', ENT_QUOTES, 'UTF-8') ?></h3>
                        <p>Premiere Date: <?= htmlspecialchars($movie['release_date'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></p>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

   <section class="daily-showings">
        <h2>Daily Showings</h2>
        <div class="movie-grid">
            <?php foreach ($dailyMovies as $movie): ?>
                <div class="movie-card">
                <a href="?page=movie_details&slug=<?= htmlspecialchars($movie['slug'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    <img src="<?= htmlspecialchars($movie['poster'] ?? '/default_poster.jpg', ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($movie['title'] ?? 'Unknown Movie', ENT_QUOTES, 'UTF-8') ?>">
                    <h3><?= htmlspecialchars($movie['title'] ?? 'Unknown Movie', ENT_QUOTES, 'UTF-8') ?></h3>
                    <p>Show Time: <?= htmlspecialchars($movie['show_time'] ?? 'TBD', ENT_QUOTES, 'UTF-8') ?></p>
                </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>







   
      


        <footer>
        <div class="footer-container">
        <!-- Virksomhedsinfo -->
        <div class="company-info">
            <h3>Om Virksomheden</h3>
            <p><?= htmlspecialchars($settings['site_title'] ?? 'Drive-In Bio') ?></p> <!-- Rettet -->
            <p>Email: <a href="mailto:<?= htmlspecialchars($settings['contact_email'] ?? '') ?>"><?= htmlspecialchars($settings['contact_email'] ?? 'Ikke angivet') ?></a></p> <!-- Rettet -->
            <p>Åbningstider: <?= htmlspecialchars($settings['opening_hours'] ?? 'Ikke angivet') ?></p> <!-- Rettet -->
            <p><?= htmlspecialchars($settings['about_content'] ?? '') ?></p> <!-- Rettet -->
        </div>
    

        <div class="contact-form">
    <h3>Kontakt Os</h3>
    <?php if (!empty($contactMessage)): ?>
        <p class="contact-message"><?= htmlspecialchars($contactMessage) ?></p>
    <?php endif; ?>
    <form method="POST" action="<?= currentPageURL('homePage') ?>">
    <div class="form-group">
        <label for="name">Navn:</label>
        <input type="text" id="name" name="name" required placeholder="Dit navn">
    </div>

    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required placeholder="Din emailadresse">
    </div>

    <div class="form-group">
        <label for="subject">Emne:</label>
        <input type="text" id="subject" name="subject" required placeholder="Emnet for din besked">
    </div>

    <div class="form-group">
        <label for="message">Besked:</label>
        <textarea id="message" name="message" rows="4" required placeholder="Skriv din besked her..."></textarea>
    </div>

    <button type="submit" name="submit">Send besked</button>
</form>

</div>










    
</footer>

    </div>
</body>