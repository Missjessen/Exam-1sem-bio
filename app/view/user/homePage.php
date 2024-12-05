
<body>

        <!-- Hero Section -->
     <section class="hero">
    <h2>Upcoming Movies</h2>
    <?php foreach ($upcomingMovies as $movie): ?>
        <div class="hero-slide">
            <img src="<?= htmlspecialchars($movie['poster']) ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
            <h3><?= htmlspecialchars($movie['title']) ?></h3>
            <p>Premiere: <?= htmlspecialchars($movie['release_date']) ?></p>
        </div>
    <?php endforeach; ?>
</section>


        <!-- News Section -->
        <section class="news">
    <h2>Latest Releases</h2>
    <div class="news-grid"> <!-- Grid-container -->
        <?php foreach ($newsMovies as $movie): ?>
            <div class="news-item">
                <img src="<?= htmlspecialchars($movie['poster']) ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
                <h3><?= htmlspecialchars($movie['title']) ?></h3>
                <p>Premiere Date: <?= htmlspecialchars($movie['release_date']) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
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


<section class="genre-selector">
    <h2>Select a Genre</h2>
    <form method="GET" action="">
        <select name="genre" onchange="this.form.submit()">
            <option value="">-- Select Genre --</option>
            <?php foreach ($allGenres as $genre): ?>
                <option value="<?= htmlspecialchars($genre['name']) ?>" <?= isset($selectedGenre) && $selectedGenre === $genre['name'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($genre['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
</section>

<?php if (!empty($moviesByGenre)): ?>
<section class="movies-by-genre">
    <h2>Movies in <?= htmlspecialchars($selectedGenre) ?></h2>
    <div class="genre-slider">
        <?php foreach ($moviesByGenre as $movie): ?>
            <div class="slide">
                <img src="<?= htmlspecialchars($movie['poster']) ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
                <h3><?= htmlspecialchars($movie['title']) ?></h3>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>
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
<style>body {
    font-family: Arial, sans-serif;
    background-color: #2e2e2e;
    color: #fff;
    margin: 0;
    padding: 0;
}

/* Container */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Navbar */
nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    background-color: #1c1c1c;
}

nav a {
    color: #fff;
    text-decoration: none;
    margin: 0 15px;
    font-weight: bold;
    transition: color 0.3s;
}

nav a:hover {
    color: #ff4c30;
}

/* Hero Section */
.upcoming {
    background-image: url('path/to/your/upcoming-image.jpg'); /* Replace with your image */
    background-size: cover;
    background-position: center;
    color: #fff;
    text-align: center;
    padding: 100px 20px;
    position: relative;
}

.upcoming h2 {
    font-size: 36px;
    margin-bottom: 20px;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.6);
}

.upcoming button {
    padding: 15px 30px;
    background-color: #ff4c30;
    border: none;
    color: #fff;
    cursor: pointer;
    font-weight: bold;
    margin: 10px;
    border-radius: 5px;
    transition: background-color 0.3s, transform 0.3s;
}

.upcoming button:hover {
    background-color: #e8432c;
    transform: scale(1.05);
}

/* News Section */
.news {
    margin: 50px 0;
}

.news h2 {
    font-size: 28px;
    text-align: center;
    margin-bottom: 20px;
}

.news-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    padding: 20px 0;
}

.news-item {
    background-color: #252525;
    border-radius: 10px;
    overflow: hidden;
    text-align: center;
    position: relative;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    transition: transform 0.3s;
}

.news-item:hover {
    transform: scale(1.05);
}

.news-item img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-bottom: 1px solid #333;
}

.news-item h3 {
    margin: 10px 0;
    font-size: 18px;
}

.news-item p {
    font-size: 14px;
    color: #bbb;
    padding: 10px;
}

/* Daily Showings Section */
.daily-showings {
    margin: 50px 0;
}

.daily-showings h2 {
    font-size: 28px;
    text-align: center;
    margin-bottom: 20px;
}

.movie-card {
    background-color: #252525;
    border-radius: 10px;
    overflow: hidden;
    text-align: center;
    position: relative;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    transition: transform 0.3s;
}

.movie-card:hover {
    transform: scale(1.05);
}

.movie-card img {
    width: 100%;
    height: 300px;
    object-fit: cover;
    border-bottom: 1px solid #333;
}

.movie-card h3 {
    margin: 15px 0 5px;
    font-size: 18px;
}

.movie-card p {
    font-size: 14px;
    color: #bbb;
}

/* Genre Slider */
.genre-movies {
    margin: 50px 0;
}

.genre-slider {
    display: flex;
    overflow-x: auto;
    gap: 20px;
    padding: 10px;
    scroll-snap-type: x mandatory;
}

.genre-slider::-webkit-scrollbar {
    height: 8px;
}

.genre-slider::-webkit-scrollbar-thumb {
    background: #444;
    border-radius: 5px;
}

.genre-slider::-webkit-scrollbar-thumb:hover {
    background: #ff4c30;
}

.slide {
    flex: 0 0 auto;
    width: 250px;
    background-color: #252525;
    border-radius: 10px;
    text-align: center;
    position: relative;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    scroll-snap-align: center;
    transition: transform 0.3s;
}

.slide:hover {
    transform: scale(1.05);
}

.slide img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-bottom: 1px solid #333;
}

.slide h3 {
    margin: 10px 0;
    font-size: 16px;
}

.slide p {
    font-size: 14px;
    color: #bbb;
    margin-bottom: 10px;
}

/* Footer */
footer {
    padding: 40px 20px;
    background-color: #1c1c1c;
    color: #bbb;
    text-align: center;
    font-size: 14px;
}

footer form input,
footer form textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: none;
    border-radius: 5px;
    background-color: #333;
    color: #fff;
}

footer form button {
    padding: 10px 20px;
    background-color: #ff4c30;
    border: none;
    color: #fff;
    cursor: pointer;
    font-weight: bold;
    border-radius: 5px;
    transition: background-color 0.3s, transform 0.3s;
}

footer form button:hover {
    background-color: #e8432c;
    transform: scale(1.05);
}

/* Cookies Banner */
.cookies-banner {
    background-color: #333;
    color: #fff;
    padding: 15px;
    position: fixed;
    bottom: 0;
    width: 100%;
    text-align: center;
    z-index: 1000;
}

.cookies-banner button {
    background-color: #ff4c30;
    border: none;
    color: #fff;
    padding: 10px 20px;
    margin-left: 10px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.cookies-banner button:hover {
    background-color: #e8432c;
}

.news-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); /* Dynamiske kolonner */
    gap: 20px; /* Afstand mellem elementer */
    padding: 20px 0; /* Rum omkring grid */
}

.news-item {
    background-color: #252525;
    border-radius: 10px;
    overflow: hidden;
    text-align: center;
    position: relative;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    transition: transform 0.3s;
    padding: 10px;
}

.news-item:hover {
    transform: scale(1.05); /* Zoom-effekt ved hover */
}

.news-item img {
    width: 100%;
    height: auto;
    max-height: 150px; /* Begræns billedhøjden */
    object-fit: cover;
    border-bottom: 1px solid #333;
}

.news-item h3 {
    margin: 10px 0;
    font-size: 18px;
}

.news-item p {
    font-size: 14px;
    color: #bbb;
}
form {
    margin-bottom: 20px;
    text-align: center;
}

select {
    padding: 10px;
    border: 1px solid #444;
    border-radius: 5px;
    background-color: #333;
    color: #fff;
    font-size: 16px;
    cursor: pointer;
}

select:hover {
    background-color: #444;
}
</style>