<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/init.php';

$movieController = new MovieAdminController($db);

$actors = $movieController->getAllActors();
$genres = $movieController->getAllGenres();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $movieId = $_POST['movie_id'] ?? null;
    $file = $_FILES['poster'] ?? null;

    $data = [
        'title' => htmlspecialchars($_POST['title']),
        'director' => htmlspecialchars($_POST['director']),
        'release_year' => htmlspecialchars($_POST['release_year']),
        'runtime' => htmlspecialchars($_POST['runtime']),
        'age_limit' => htmlspecialchars($_POST['age_limit']),
        'description' => htmlspecialchars($_POST['description']),
    ];

    if ($action === 'create') {
        $actorIds = $_POST['actor_ids'] ?? [];
        $genreIds = $_POST['genre_ids'] ?? [];
        $movieController->createMovie($data, $file, $actorIds, $genreIds, $_POST['new_actors'], $_POST['new_genres']);
    } elseif ($action === 'update') {
        $actorIds = $_POST['actor_ids'] ?? [];
        $genreIds = $_POST['genre_ids'] ?? [];
        $movieController->updateMovie($movieId, $data, $file, $actorIds, $genreIds);
    } elseif ($action === 'delete') {
        $movieController->deleteMovie($movieId);
    }

    header("Location: admin_movie.php");
    exit;
}

if (isset($_POST['action']) && $_POST['action'] === 'edit') {
    $movieToEdit = $movieController->getMovie($_POST['movie_id']);
}

$movies = $movieController->getAllMovies();
?>

<h1>Film Administration</h1>

<div class="container">
    <section id="existing-movies">
        <h2>Eksisterende Film</h2>
        <div class="search-bar">
            <input type="text" id="search" placeholder="Søg efter film..." onkeyup="filterMovies()">
        </div>
        <div class="movies-list">
            <?php foreach ($movies as $movie): ?>
                <div class="movie-item">
                    <img src="<?= $movie['poster'] ?>" alt="Film Plakat" class="movie-poster">
                    <div class="movie-details">
                        <h3><?= htmlspecialchars($movie['title']) ?></h3>
                        <p>Instruktør: <?= htmlspecialchars($movie['director']) ?></p>
                        <p>År: <?= htmlspecialchars($movie['release_year']) ?></p>
                        <p>Varighed: <?= htmlspecialchars($movie['runtime']) ?> minutter</p>
                        <p>Aldersgrænse: <?= htmlspecialchars($movie['age_limit']) ?></p>
                        <p>Beskrivelse: <?= htmlspecialchars($movie['description']) ?></p>
                        <p>Genrer: <?= implode(', ', $movieController->getGenresByMovie($movie['id'])) ?></p>
                        <p>Skuespillere: <?= implode(', ', $movieController->getActorsByMovie($movie['id'])) ?></p>

                        <form action="admin_movie.php" method="post">
                            <input type="hidden" name="movie_id" value="<?= $movie['id'] ?>">
                            <button type="submit" name="action" value="edit">Rediger</button>
                            <button type="submit" name="action" value="delete" onclick="return confirm('Er du sikker på, at du vil slette denne film?');">Slet</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section id="create-movie">
        <h2><?= isset($movieToEdit) ? 'Rediger Film' : 'Opret Ny Film' ?></h2>
        <form action="admin_movie.php" method="post" enctype="multipart/form-data">
            <label for="title">Titel:</label>
            <input type="text" id="title" name="title" required>

            <label for="director">Instruktør:</label>
            <input type="text" id="director" name="director" required>

            <label for="release_year">Udgivelsesår:</label>
            <input type="number" id="release_year" name="release_year" required>

            <label for="runtime">Varighed:</label>
            <input type="number" id="runtime" name="runtime" required>

            <label for="age_limit">Aldersgrænse:</label>
            <input type="number" id="age_limit" name="age_limit" required>

            <label for="description">Beskrivelse:</label>
            <textarea id="description" name="description" required></textarea>

            <label for="poster">Filmplakat:</label>
            <input type="file" id="poster" name="poster" accept="image/*">

            <label for="actors">Vælg eksisterende skuespillere:</label>
            <select name="actor_ids[]" multiple>
                <?php foreach ($actors as $actor): ?>
                    <option value="<?= $actor['id'] ?>"><?= htmlspecialchars($actor['name']) ?></option>
                <?php endforeach; ?>
            </select>

            <label for="new_actors">Tilføj nye skuespillere (kommasepareret):</label>
            <input type="text" id="new_actors" name="new_actors" placeholder="F.eks. Brad Pitt, Tom Hanks">

            <label for="genres">Vælg eksisterende genrer:</label>
            <select name="genre_ids[]" multiple>
                <?php foreach ($genres as $genre): ?>
                    <option value="<?= $genre['id'] ?>"><?= htmlspecialchars($genre['name']) ?></option>
                <?php endforeach; ?>
            </select>

            <label for="new_genres">Tilføj nye genrer (kommasepareret):</label>
            <input type="text" id="new_genres" name="new_genres" placeholder="F.eks. Thriller, Komedie">

            <button type="submit" name="action" value="<?= isset($movieToEdit) ? 'update' : 'create' ?>">
                <?= isset($movieToEdit) ? 'Opdater Film' : 'Opret Film' ?>
            </button>
        </form>
    </section>
</div>

<script>
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
#create-movie input[type="file"] {
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    width: 100%;
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

/* Stil for rediger og slet knapper */
.movie-item form button {
    padding: 6px 12px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.movie-item form button[type="submit"][value="edit"] {
    background-color: #28a745;
    color: white;
}

.movie-item form button[type="submit"][value="delete"] {
    background-color: #dc3545;
    color: white;
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
  
</style>