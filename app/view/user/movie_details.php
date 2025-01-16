<?php
$current_slug = 'test-slug';
?>
<div class="movie-details">
    <h1><?= htmlspecialchars($movie['title']) ?></h1>
    <img src="<?= htmlspecialchars($movie['poster'] ?? '/default_poster.jpg') ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
    <p><strong>Beskrivelse:</strong> <?= nl2br(htmlspecialchars($movie['description'] ?? 'Ingen beskrivelse tilgængelig')) ?></p>
    <p><strong>Genre:</strong> <?= htmlspecialchars($movie['genre'] ?? 'Ikke angivet') ?></p>
    <p><strong>Skuespillere:</strong> <?= htmlspecialchars($movie['actors'] ?? 'Ikke angivet') ?></p>

    <!-- Visningstider -->
    <h2>Visningstider</h2>
    <?php if (!empty($showtimes)): ?>
        <ul>
            <?php foreach ($showtimes as $showing): ?>
                <li>
                    <?= htmlspecialchars($showing['show_date']) ?> kl. <?= htmlspecialchars($showing['show_time']) ?> 
                    (Skærm: <?= htmlspecialchars($showing['screen']) ?>, Ledige pladser: <?= htmlspecialchars($showing['available_spots']) ?>)
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Der er ingen planlagte visninger for denne film i øjeblikket.</p>
    <?php endif; ?>
</div>

<!-- Bookingformular -->
<h2>Bookingformular</h2>
<form method="POST" action="<?= BASE_URL ?>index.php?page=handle_booking" class="booking-form">
    <label for="showtime">Vælg spilletid:</label>
    <select name="showing_id" id="showtime" required>
        <?php if (!empty($showtimes)): ?>
            <?php foreach ($showtimes as $showing): ?>
                <option value="<?= htmlspecialchars($showing['showing_id']) ?>">
                    <?= htmlspecialchars($showing['show_date']) ?> kl. <?= htmlspecialchars($showing['show_time']) ?>
                    (Skærm: <?= htmlspecialchars($showing['screen'] ?? 'Ukendt skærm') ?>, Ledige pladser: <?= htmlspecialchars($showing['available_spots']) ?>)
                </option>
            <?php endforeach; ?>
        <?php else: ?>
            <option value="">Ingen tilgængelige spilletider</option>
        <?php endif; ?>
    </select>

    <label for="spots">Antal pladser:</label>
    <input type="number" id="spots" name="spots" min="1" max="10" required>

    <button type="submit" class="btn btn-primary">Book nu</button>
</form>

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
.movie-details img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.6);
    border: 3px solid #f39c12;
}

/* Bookingformular */
.booking-form {
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    background-color: #1a1a1a; /* Mørk grå baggrund */
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.4);
}

.booking-form label {
    font-size: 1rem;
    margin-bottom: 5px;
    display: block;
    color: #f39c12;
}

.booking-form select,
.booking-form input[type="number"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    font-size: 1rem;
    border: 2px solid #f39c12;
    border-radius: 5px;
    background-color: #1a1a1a;
    color: #f6f6f6;
    outline: none;
    transition: border-color 0.3s ease;
}

.booking-form select:focus,
.booking-form input[type="number"]:focus {
    border-color: #e67e22;
}

.booking-form button {
    display: block;
    width: 100%;
    padding: 10px;
    font-size: 1rem;
    font-weight: bold;
    text-transform: uppercase;
    color: #fff;
    background-color: #f39c12;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.booking-form button:hover {
    background-color: #e67e22;
}
</style>
