<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/init.php'; // Inkluder init.php med $db og autoloader


$reviewController = new ReviewController($db); // Initialiser ReviewController med databaseforbindelsen
$movie_uuid = $movie['id']; // Antag, at 'id' er UUID for filmen


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

<?php
// Håndter anmeldelsesindsendelse
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'add_review') {
    $data = [
        'movie_id' => $movie_uuid, // Brug UUID fra filmen
        'customer_id' => $_SESSION['customer_id'], // Kundens ID fra sessionen
        'rating' => intval($_POST['rating']),
        'comment' => $_POST['comment']
    ];

    // Valider og tilføj anmeldelsen via ReviewController
    $resultMessage = $reviewController->addReview($data);
    echo "<p>" . htmlspecialchars($resultMessage) . "</p>";
}

// Vis eksisterende anmeldelser for filmen
echo "<h3>Anmeldelser</h3>";
$reviews = $reviewController->getReviews($movie_uuid); // Hent anmeldelser baseret på UUID
if (!empty($reviews)) {
    foreach ($reviews as $review) {
        echo "<p><strong>" . htmlspecialchars($review['customer_name']) . "</strong></p>";
        echo "<p>Vurdering: " . $review['rating'] . "/5</p>";
        echo "<p>Kommentar: " . htmlspecialchars($review['comment']) . "</p>";
        echo "<p><small>Dato: " . $review['created_at'] . "</small></p><hr>";
    }
} else {
    echo "<p>Ingen anmeldelser endnu.</p>";
}

// Formular til ny anmeldelse
echo '<h3>Skriv en Anmeldelse</h3>';
echo '<form action="movieDetails.php?uuid=' . $movie_uuid . '" method="POST">
    <label for="rating">Vurdering (1-5):</label>
    <select id="rating" name="rating" required>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
    </select>

    <label for="comment">Kommentar:</label>
    <textarea id="comment" name="comment" rows="4" required></textarea>

    <button type="submit" name="action" value="add_review">Tilføj Anmeldelse</button>
</form>';
?>