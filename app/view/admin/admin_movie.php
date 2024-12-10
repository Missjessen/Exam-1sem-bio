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
                        <h3><?= htmlspecialchars($movie['title']) ?></h3>
                        <p>Instruktør: <?= htmlspecialchars($movie['director'] ?? 'Ikke angivet') ?></p>
                        <p>År: <?= htmlspecialchars($movie['release_year'] ?? 'Ikke angivet') ?></p>
                        <p>Varighed: <?= htmlspecialchars($movie['length'] ?? 'Ikke angivet') ?> minutter</p>
                        <p>Aldersgrænse: <?= htmlspecialchars($movie['age_limit'] ?? 'Ikke angivet') ?></p>
                        <p>Beskrivelse: <?= htmlspecialchars($movie['description'] ?? 'Ikke angivet') ?></p>
                        <p>Præmiere dato: <?= htmlspecialchars($movie['premiere_date'] ?? 'Ikke angivet') ?></p>
                        <p>Sprog: <?= htmlspecialchars($movie['language'] ?? 'Ikke angivet') ?></p>
                        <p>Genrer: <?= htmlspecialchars($movie['genres'] ?? 'Ingen') ?></p>
                        <p>Skuespillere: <?= htmlspecialchars($movie['actors'] ?? 'Ingen') ?></p>

                        <form action="/?page=admin_movie" method="post">
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
        <form action="?page=admin_movie" method="post" enctype="multipart/form-data">
            <input type="hidden" name="movie_id" value="<?= isset($movieToEdit) ? htmlspecialchars($movieToEdit['id']) : '' ?>">

            <label for="title">Titel:</label>
            <input type="text" id="title" name="title" value="<?= $movieToEdit['title'] ?? '' ?>" required>

            <label for="director">Instruktør:</label>
            <input type="text" id="director" name="director" value="<?= $movieToEdit['director'] ?? '' ?>" required>

            <label for="release_year">År:</label>
            <input type="number" id="release_year" name="release_year" value="<?= $movieToEdit['release_year'] ?? '' ?>" required>

            <label for="length">Varighed (minutter):</label>
            <input type="number" id="length" name="length" value="<?= $movieToEdit['length'] ?? '' ?>" required>

            <label for="age_limit">Aldersgrænse:</label>
            <input type="number" id="age_limit" name="age_limit" value="<?= $movieToEdit['age_limit'] ?? '' ?>" required>

            <label for="description">Beskrivelse:</label>
            <textarea id="description" name="description" required><?= $movieToEdit['description'] ?? '' ?></textarea>

            <label for="premiere_date">Præmiere dato:</label>
            <input type="date" id="premiere_date" name="premiere_date" value="<?= $movieToEdit['premiere_date'] ?? '' ?>" required>

            <label for="language">Sprog:</label>
            <input type="text" id="language" name="language" value="<?= $movieToEdit['language'] ?? '' ?>" required>

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

<style>
/* Generel stil for hele siden */
body {
    font-family: Arial, sans-serif;
    margin: 20px;
    background-color: #f4f4f4;
    color: #333;
}

h1, h2 {
    color: #444;
    text-align: center;
}

.container {
    display: flex;
    justify-content: space-between;
    gap: 20px;
    flex-wrap: wrap;
}

/* Stil til eksisterende film sektionen */
#existing-movies {
    flex: 1;
    background-color: #ffffff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

#existing-movies .movies-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin-top: 20px;
}

.movie-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background-color: #f9f9f9;
}

.movie-item img {
    width: 80px;
    height: auto;
    border-radius: 4px;
}

.movie-details {
    flex: 1;
}

.movie-details h3 {
    margin: 0;
    font-size: 1.2em;
}

.movie-details p {
    margin: 5px 0;
    font-size: 0.9em;
    color: #666;
}

.search-bar {
    margin-bottom: 20px;
    text-align: center;
}

.search-bar input[type="text"] {
    padding: 8px;
    width: 80%;
    border: 1px solid #ddd;
    border-radius: 4px;
}

/* Stil til opret film sektionen */
#create-movie {
    flex: 1;
    background-color: #ffffff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

#create-movie form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

#create-movie label {
    font-weight: bold;
    margin-bottom: 5px;
}

#create-movie input[type="text"],
#create-movie input[type="number"],
#create-movie textarea,
#create-movie input[type="file"],
#create-movie select {
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    width: 100%;
}

#create-movie select {
    height: auto;
    padding: 6px;
}

#create-movie button {
    padding: 10px;
    background-color: #007bff;
    color: #ffffff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

#create-movie button:hover {
    background-color: #0056b3;
}

/* Stil for nye skuespillere og genrer */
/* Form til nye skuespillere og genrer */
#additional-forms {
    margin-top: 20px;
}

#additional-forms form {
    display: flex;
    flex-direction: column;
    gap: 10px;
    background-color: #fff;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

#additional-forms label {
    font-weight: bold;
    margin-bottom: 5px;
}

#additional-forms input[type="text"] {
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    width: 100%;
}

#additional-forms button {
    margin-top: 10px;
    padding: 10px;
    background-color: #28a745;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

#additional-forms button:hover {
    background-color: #218838;
}

/* Responsiv stil */
@media (max-width: 768px) {
    .container {
        flex-direction: column;
    }

    .movie-item {
        flex-direction: column;
        align-items: flex-start;
    }

    .search-bar input[type="text"] {
        width: 100%;
    }
}

.select2-container {
        width: 100% !important;
    }
    .select2-selection {
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }</style>