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



