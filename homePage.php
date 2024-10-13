
<?php 

// Inkluder header og databaseforbindelse ved hjælp af absolut sti
include $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/includes/header.php'; 
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/includes/connection.php'; 

// (rest of your code here)

// Inkluder funktioner
$page = $_GET['page'] ?? 'homePage';
?>
</main>
  


<body>
    <!-- Cookies Banner -->
    <div class="cookies-banner">
        <p>Vi bruger cookies til at forbedre din oplevelse. <a href="#">Læs mere</a></p>
        <button>Accepter</button>
    </div>
    <!-- Upcoming Section -->
    <section class="upcoming">
        <h2>Upcoming</h2>
        <button>Book Your Spot</button>
        <p>(date-time-screen)</p>
    </section>

    <!-- News Section -->
    <section class="news">
        <h2>News</h2>
        <div class="news-grid">
            <!-- Repeat for each news item -->
            <div class="news-item"></div>
            <div class="news-item"></div>
            <div class="news-item"></div>
            <div class="news-item"></div>
        </div>
    </section>

    <!-- Top 5 Rating Section -->
    <section class="top-5">
        <h2>Top 5 Rating</h2>
        <div class="top-5-grid">
            <!-- Repeat for each top-rated item -->
            <div class="movie-card"></div>
            <div class="movie-card"></div>
            <div class="movie-card"></div>
            <div class="movie-card"></div>
            <div class="movie-card"></div>
        </div>
    </section>

    <!-- All Movies Section -->
    <section class="all-movies">
        <h2>Movies</h2>
        <div class="filter-options">
            <button>All Movies</button>
            <!-- Add filter buttons as needed -->
        </div>
        <div class="movies-grid">
            <!-- Repeat for each movie -->
            <div class="movie-card"></div>
            <div class="movie-card"></div>
            <div class="movie-card"></div>
            <div class="movie-card"></div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <!-- Footer Links and Other Content -->
        </div>
    </footer>
</body>
</html>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/includes/footer.php';?>