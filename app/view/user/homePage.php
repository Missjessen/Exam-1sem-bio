<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/init.php'; // Inkluder init.php med $db og autoloader
?>

<body>
    <div class="container">
        <!-- Cookies Banner -->
        <div class="cookies-banner">
            <p>Vi bruger cookies til at forbedre din oplevelse. <a href="#">Læs mere</a></p>
            <button>Accepter</button>
        </div>

        <!-- Upcoming Section -->
        <section class="upcoming">
            <h2>Upcoming</h2>
            <?php foreach ($upcomingMovies as $movie): ?>
                <div class="movie-card">
                    <img src="<?php echo htmlspecialchars($movie['image']); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>">
                    <h3><?php echo htmlspecialchars($movie['title']); ?></h3>
                    <p>Premiere Date: <?php echo htmlspecialchars($movie['release_date']); ?></p>
                    <button>Book Your Spot</button>
                </div>
            <?php endforeach; ?>
        </section>

        <!-- News Section -->
        <section class="news">
            <h2>News</h2>
            <div class="news-slider">
                <?php foreach ($newsMovies as $movie): ?>
                    <div class="news-item">
                        <img src="<?php echo htmlspecialchars($movie['image']); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>">
                        <h3><?php echo htmlspecialchars($movie['title']); ?></h3>
                        <p><?php echo htmlspecialchars(substr($movie['description'], 0, 100)); ?>...</p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Top 5 Rating Section -->
        <section class="top-5">
            <h2>Top 5 Rating</h2>
            <div class="top-5-grid">
                <?php foreach ($top5Movies as $movie): ?>
                    <div class="movie-card">
                        <img src="<?php echo htmlspecialchars($movie['image']); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>">
                        <div class="rating"><?php echo htmlspecialchars($movie['rating']); ?></div>
                        <h3><?php echo htmlspecialchars($movie['title']); ?></h3>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- All Movies Section -->
        <section class="all-movies container">
            <h2>Movies</h2>
            <div class="filter-options">
                <a href="?genre=">All Movies</a>
                <a href="?genre=Action">Action</a>
                <a href="?genre=Drama">Drama</a>
                <a href="?genre=Comedy">Comedy</a>
                <!-- Tilføj flere genrer efter behov -->
            </div>
            <div class="movies-grid">
                <?php foreach ($allMovies as $movie): ?>
                    <div class="movie-card">
                        <img src="<?php echo htmlspecialchars($movie['image']); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>">
                        <div class="rating"><?php echo htmlspecialchars($movie['rating']); ?></div>
                        <h3><?php echo htmlspecialchars($movie['title']); ?></h3>
                        <p><?php echo htmlspecialchars($movie['genre']); ?></p>
                        <a href="/movie/<?php echo htmlspecialchars($movie['uuid']); ?>/<?php echo htmlspecialchars($movie['slug']); ?>">View Details</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </div>
</body>



<style>/* General Styles */
/* General container for page margins */
/* General Styles */
body {
    font-family: Arial, sans-serif;
    background-color: #181818;
    color: #fff;
    margin: 0;
    padding: 0;
}

/* Container for page margins */
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
    background-color: #202020;
}

nav a {
    color: #fff;
    text-decoration: none;
    margin: 0 15px;
    font-weight: bold;
}

/* Upcoming Section */
.upcoming {
    background-image: url('path/to/your/image.jpg'); /* Replace with your image URL */
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
}

/* Movie Grid Styles */
.movies-grid, .news-grid, .top-5-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 20px;
    padding: 20px 0;
}

.movie-card, .news-item {
    background-color: #252525;
    border-radius: 10px;
    overflow: hidden;
    text-align: center;
    position: relative;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    transition: transform 0.3s;
}

.movie-card:hover, .news-item:hover {
    transform: scale(1.05);
}

.movie-card img {
    width: 100%;
    height: 300px;
    object-fit: cover;
    border-bottom: 1px solid #333;
}

.movie-card .details {
    padding: 15px;
}

.movie-card .rating {
    position: absolute;
    top: 10px;
    left: 10px;
    background-color: #ff4c30;
    color: #fff;
    padding: 5px 10px;
    border-radius: 5px;
    font-weight: bold;
}

.movie-card h3 {
    margin: 15px 0 5px;
    font-size: 18px;
}

.movie-card p {
    font-size: 14px;
    color: #bbb;
}

/* Filter and Sorting Options */
.filter-options {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin-bottom: 20px;
}

.filter-options button {
    background-color: #444;
    color: #fff;
    border: none;
    padding: 8px 15px;
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.filter-options button:hover {
    background-color: #ff4c30;
}

/* Footer */
footer {
    padding: 40px 20px;
    background-color: #202020;
    color: #bbb;
    text-align: center;
    font-size: 14px;
}


</style>