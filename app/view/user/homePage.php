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




<section class="genre-selector">
    <h2>Select a Genre</h2>
    <form method="GET" action="">
        <select name="genre" onchange="this.form.submit()">
            <option value="">-- Select Genre --</option>
            <?php foreach ($allGenres as $genre): ?>
                <option value="<?= htmlspecialchars($genre['name'] ?? '') ?>" <?= isset($selectedGenre) && $selectedGenre === ($genre['name'] ?? '') ? 'selected' : '' ?>>
                    <?= htmlspecialchars($genre['name'] ?? '') ?>
                </option> <!-- Rettet -->
            <?php endforeach; ?>
        </select>
    </form>
</section>

<?php if (!empty($moviesByGenre)): ?>
<section class="movies-by-genre">
    <h2>Movies in <?= htmlspecialchars($selectedGenre ?? '') ?></h2> <!-- Rettet -->
    <div class="genre-slider">
        <?php foreach ($moviesByGenre as $movie): ?>
            <div class="slide">
                <img src="<?= htmlspecialchars($movie['poster'] ?? '') ?>" alt="<?= htmlspecialchars($movie['title'] ?? '') ?>"> <!-- Rettet -->
                <h3><?= htmlspecialchars($movie['title'] ?? '') ?></h3> <!-- Rettet -->
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>
        <section class="selected-genre-movies">
    <h2><?= htmlspecialchars($selectedGenre ?? '') ?> Movies</h2> <!-- Rettet -->
    <div class="genre-slider">
        <?php foreach ($genreMovies as $movie): ?>
            <div class="slide">
                <img src="<?= htmlspecialchars($movie['poster'] ?? '') ?>" alt="<?= htmlspecialchars($movie['title'] ?? '') ?>"> <!-- Rettet -->
                <h3><?= htmlspecialchars($movie['title'] ?? '') ?></h3> <!-- Rettet -->
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
                <img src="<?= htmlspecialchars($movie['poster'] ?? '') ?>" alt="<?= htmlspecialchars($movie['title'] ?? '') ?>"> <!-- Rettet -->
                <h3><?= htmlspecialchars($movie['title'] ?? '') ?></h3> <!-- Rettet -->
                <p>Genre: <?= htmlspecialchars($movie['genre'] ?? '') ?></p> <!-- Rettet -->
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
    

        <?php if (isset($feedback)) : ?>
    <div class="feedback">
        <p><?php echo htmlspecialchars($feedback, ENT_QUOTES, 'UTF-8'); ?></p>
    </div>
<?php endif; ?>

<?php if (!empty($contactMessage)): ?>
    <p class="contact-message"><?= htmlspecialchars($contactMessage) ?></p>
<?php endif; ?>

<div class="contact-form">
    <h3>Kontakt Os</h3>
    <form method="POST">
        <!-- Navn -->
        <div class="form-group">
            <label for="name">Navn:</label>
            <input 
                type="text" 
                id="name" 
                name="name" 
                required 
                placeholder="Indtast dit navn"
            >
        </div>

        <!-- Email -->
        <div class="form-group">
            <label for="email">Email:</label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                required 
                placeholder="Indtast din email fx: navn@domæne.dk"
            >
        </div>

        <!-- Emne -->
        <div class="form-group">
            <label for="subject">Emne:</label>
            <input 
                type="text" 
                id="subject" 
                name="subject" 
                required 
                placeholder="Indtast emnet for din besked"
            >
        </div>

        <!-- Besked -->
        <div class="form-group">
            <label for="message">Besked:</label>
            <textarea 
                id="message" 
                name="message" 
                rows="4" 
                required 
                placeholder="Skriv din besked her..."
            ></textarea>
        </div>

        <!-- Submit-knap -->
        <button type="submit" name="submit">Send</button>
    </form>
</div>




    
</footer>

    </div>
</body>
