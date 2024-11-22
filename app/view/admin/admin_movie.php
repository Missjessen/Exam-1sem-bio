<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/init.php';
// Test databaseforbindelse
 try {
    $db = new PDO(DSN, DB_USER, DB_PASS);
    $stmt = $db->query("SELECT * FROM movies");
    $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
    var_dump($movies); // Debugging: Tjek, om data bliver hentet
} catch (PDOException $e) {
    die("Fejl ved databaseforbindelse: " . $e->getMessage());
}

// Dummy data for skuespillere og genrer
$actors = [['id' => 1, 'name' => 'Test Skuespiller']];
$genres = [['id' => 1, 'name' => 'Test Genre']];  
?>



<h1>Film Administration</h1>

<div class="container">
    <!-- Sektion: Eksisterende film -->
    <section id="existing-movies">
        <h2>Eksisterende Film</h2>
        <div class="search-bar">
            <input type="text" id="search" placeholder="Søg efter film..." onkeyup="filterMovies()">
        </div>
        <div class="movies-list">
    <?php if (!empty($movies)): ?>
        <?php foreach ($movies as $movie): ?>
            <div class="movie-item">
                <img src="<?= htmlspecialchars($movie['poster']) ?>" alt="Film Plakat" class="movie-poster">
                <div class="movie-details">
                <h2><?= htmlspecialchars($movie['title'], ENT_QUOTES, 'UTF-8'); ?></h2>
                        <p>Instruktør: <?= htmlspecialchars($movie['director'], ENT_QUOTES, 'UTF-8') ?></p>
                        <p>År: <?= htmlspecialchars($movie['release_year'], ENT_QUOTES, 'UTF-8') ?></p>
                        <p>Varighed: <?= htmlspecialchars($movie['runtime'], ENT_QUOTES, 'UTF-8') ?> minutter</p>
                        <p>Aldersgrænse: <?= htmlspecialchars($movie['age_limit'], ENT_QUOTES, 'UTF-8') ?></p>
                        <p>Beskrivelse: <?= htmlspecialchars($movie['description'], ENT_QUOTES, 'UTF-8') ?></p>
                        <p>Varighed: <?= htmlspecialchars($movie['length'], ENT_QUOTES, 'UTF-8') ?> minutter</p>
                        <p>Præmiere dato: <?= htmlspecialchars($movie['premiere_date'], ENT_QUOTES, 'UTF-8') ?></p>
                        <p>Sprog: <?= htmlspecialchars($movie['language'], ENT_QUOTES, 'UTF-8') ?></p>
                        <p>Genrer: <?= implode(', ', array_column($genres, 'name')) ?></p>
                        <p>Skuespillere: <?= implode(', ', array_column($actors, 'name')) ?></p>

                        <form action="/Exam-1sem-bio/index.php?page=admin_movie" method="post">
                            <input type="hidden" name="movie_id" value="<?= htmlspecialchars($movie['id'], ENT_QUOTES, 'UTF-8') ?>">
                            <button type="submit" name="action" value="edit">Rediger</button>
                            <button type="submit" name="action" value="delete" onclick="return confirm('Er du sikker på, at du vil slette denne film?');">Slet</button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
    <?php else: ?>
        <p>Ingen film fundet.</p>
    <?php endif; ?>
</div>
    </section>

    <!-- Sektion: Opret/rediger film -->
    <div class="container">
    <!-- Section: Create/Edit Movie -->
    <section id="create-movie">
        <h2><?= isset($movieToEdit) ? 'Rediger Film' : 'Opret Ny Film' ?></h2>
        <form action="/Exam-1sem-bio/index.php?page=admin_movie" method="post" enctype="multipart/form-data">
            <input type="hidden" name="movie_id" value="<?= isset($movieToEdit) ? htmlspecialchars($movieToEdit['id']) : '' ?>">

            <label for="title">Titel:</label>
            <input type="text" id="title" name="title" value="<?= isset($movieToEdit) ? htmlspecialchars($movieToEdit['title']) : '' ?>" required>

            <label for="director">Instruktør:</label>
            <input type="text" id="director" name="director" value="<?= isset($movieToEdit) ? htmlspecialchars($movieToEdit['director']) : '' ?>" required>

            <label for="release_year">Udgivelsesår:</label>
            <input type="number" id="release_year" name="release_year" value="<?= isset($movieToEdit) ? htmlspecialchars($movieToEdit['release_year']) : '' ?>" required>

            <label for="length">Varighed:</label>
            <input type="number" id="length" name="length" value="<?= isset($movieToEdit) ? htmlspecialchars($movieToEdit['length']) : '' ?>" required>

            <label for="age_limit">Aldersgrænse:</label>
            <input type="number" id="age_limit" name="age_limit" value="<?= isset($movieToEdit) ? htmlspecialchars($movieToEdit['age_limit']) : '' ?>" required>

            <label for="description">Beskrivelse:</label>
            <textarea id="description" name="description" required><?= isset($movieToEdit) ? htmlspecialchars($movieToEdit['description']) : '' ?></textarea>

            <label for="premiere_date">Præmiere dato:</label>
            <input type="date" id="premiere_date" name="premiere_date" value="<?= isset($movieToEdit) ? htmlspecialchars($movieToEdit['premiere_date']) : '' ?>" required>

            <label for="language">Sprog:</label>
            <input type="text" id="language" name="language" value="<?= isset($movieToEdit) ? htmlspecialchars($movieToEdit['language']) : '' ?>" required>

            

            <label for="poster">Filmplakat:</label>
            <input type="file" id="poster" name="poster" accept="image/*">

<!-- Skuespillere -->
<label for="actors">Vælg skuespillere:</label>
<select id="actors" name="actor_ids[]" class="select2-multiple" multiple>
    <?php foreach ($actors as $actor): ?>
        <option value="<?= htmlspecialchars($actor['id']) ?>"
            <?= isset($movieToEdit) && in_array($actor['id'], array_column($movieToEdit['actors'], 'id')) ? 'selected' : '' ?>>
            <?= htmlspecialchars($actor['name']) ?>
        </option>
    <?php endforeach; ?>
</select>

<!-- Genrer -->
<label for="genres">Vælg genrer:</label>
<select id="genres" name="genre_ids[]" class="select2-multiple" multiple>
    <?php foreach ($genres as $genre): ?>
        <option value="<?= htmlspecialchars($genre['id']) ?>"
            <?= isset($movieToEdit) && in_array($genre['id'], array_column($movieToEdit['genres'], 'id')) ? 'selected' : '' ?>>
            <?= htmlspecialchars($genre['name']) ?>
        </option>
    <?php endforeach; ?>
</select>

        <!-- Knappen til at oprette eller opdatere -->
        <button type="submit" name="action" value="<?= isset($movieToEdit) ? 'update' : 'create' ?>">
            <?= isset($movieToEdit) ? 'Opdater Film' : 'Opret Film' ?>
        </button>
    </form>
</section>
   
    <!-- Sektion til at tilføje nye skuespillere og genrer -->
  <!-- Sektion til at tilføje nye skuespillere og genrer -->
  <section id="additional-forms">
        <h2>Tilføj ny skuespiller eller genre</h2>

        <!-- Form til at tilføje ny skuespiller -->
        <form action="admin_movie.php" method="post">
            <label for="actor_name">Tilføj ny skuespiller:</label>
            <input type="text" id="actor_name" name="actor_name" placeholder="Indtast skuespillers navn" required>
            <button type="submit" name="action" value="create_actor">Tilføj Skuespiller</button>
        </form>

        <!-- Form til at tilføje ny genre -->
        <form action="admin_movie.php" method="post">
            <label for="genre_name">Tilføj ny genre:</label>
            <input type="text" id="genre_name" name="genre_name" placeholder="Indtast genrenavn" required>
            <button type="submit" name="action" value="create_genre">Tilføj Genre</button>
        </form>
    </section>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
 $(document).ready(function () {
    $('.actor-select').select2({
        placeholder: "Vælg skuespillere",
        allowClear: true,
        width: '100%'
    });

    $('.genre-select').select2({
        placeholder: "Vælg genrer",
        allowClear: true,
        width: '100%'
    });
});
   function filterMovies() {
        const searchValue = document.getElementById('search').value.toLowerCase();
        const movies = document.querySelectorAll('.movie-item');

        movies.forEach(movie => {
            const title = movie.querySelector('.movie-details h3').textContent.toLowerCase();
            movie.style.display = title.includes(searchValue) ? 'flex' : 'none';
        });
    } 
</script>




