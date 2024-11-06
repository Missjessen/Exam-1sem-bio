<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/init.php'; // Inkluder init.php med $db og autoloader

if ($movie): ?>
    <div class="movie-details">
        <h1><?php echo htmlspecialchars($movie['title']); ?></h1>
        <img src="<?php echo htmlspecialchars($movie['image']); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>">
        <p>Release Date: <?php echo htmlspecialchars($movie['release_date']); ?></p>
        <p>Genre: <?php echo htmlspecialchars($movie['genre']); ?></p>
        <p>Rating: <?php echo htmlspecialchars($movie['rating']); ?></p>
        <p>Description: <?php echo htmlspecialchars($movie['description']); ?></p>
    </div>
<?php else: ?>
    <p>Film ikke fundet.</p>
<?php endif; ?>
