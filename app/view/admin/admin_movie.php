<?php
session_start();

// Inkluder databaseforbindelse og autoloader
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/config/connection.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/core/autoloader.php';

// Inkluder FileUploadService-filen
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/app/controllers/fileUploader.php';

// Opret en instans af controlleren og filuploadservicen
$controller = new AdminController($db);
$fileUploadService = new FileUploadService(); // Tilføj filuploadservicen

// Håndter forskellige CRUD-operationer baseret på handling
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        handlePostAction($controller, $fileUploadService, $_POST['action']);

        // Genindlæs siden efter `POST`-anmodningen for at forhindre gentagelse ved opdatering
        header("Location: admin_movie.php");
        exit;
    }
}

/**
 * Håndterer forskellige CRUD-operationer baseret på handling
 */
function handlePostAction($controller, $fileUploadService, $action) {
    $poster_path = '';
    try {
        $poster_path = $fileUploadService->uploadFile($_FILES['poster']);
    } catch (Exception $e) {
        echo $e->getMessage();
    }

    switch ($action) {
        case 'create':
            $data = prepareMovieData($poster_path);
            $controller->createMovie($data);
            break;

        case 'update':
            $id = intval($_POST['movie_id']);
            $data = prepareMovieData($poster_path, true);
            $controller->updateMovie($id, $data);
            break;

        case 'delete':
            $id = intval($_POST['movie_id']);
            $controller->deleteMovie($id);
            break;

        default:
            echo "Ugyldig handling!";
    }
}

/**
 * Forbereder data til oprettelse eller opdatering af en film.
 */
function prepareMovieData($poster_path, $isUpdate = false) {
    $data = [
        'age_limit' => $_POST['age_limit'],
        'title' => $_POST['title'],
        'director' => $_POST['director'],
        'release_year' => $_POST['release_year'],
        'runtime' => $_POST['runtime'],
        'description' => $_POST['description']
    ];

    if ($poster_path && !$isUpdate) {
        $data['poster'] = $poster_path; // Inkludér altid billede for oprettelse
    } elseif ($poster_path && $isUpdate) {
        $data['poster'] = $poster_path; // Kun opdatér billede, hvis der er et nyt upload
    }

    return $data;
}
?>


<h1>Film Administration</h1>

<div class="container">
    <!-- Vis eksisterende film -->
    <section id="existing-movies">
        <h2>Eksisterende Film</h2>
        <div class="search-bar">
            <input type="text" id="search" placeholder="Søg efter film..." onkeyup="filterMovies()">
        </div>
        <div class="movies-list" id="movies-list">
            <?php
            // Brug controlleren til at hente alle film
            $movies = $controller->getAllMovies();
            if (!empty($movies)) {
                foreach ($movies as $movie) {
                    echo "
                    <div class='movie-item' data-title='{$movie['title']}'>
                        <img src='{$movie['poster']}' alt='Film Plakat' class='movie-poster'>
                        <div class='movie-details'>
                            <h3>{$movie['title']}</h3>
                            <p>Instruktør: {$movie['director']}</p>
                            <p>År: {$movie['release_year']}</p>
                            <p>Varighed: {$movie['runtime']} minutter</p>
                            <p>Aldersgrænse: {$movie['age_limit']}</p>
                            <p>Beskrivelse: {$movie['description']}</p>
                        </div>
                        <form action='admin_movie.php' method='post'>
                            <input type='hidden' name='movie_id' value='{$movie['movie_id']}'>
                            <button type='submit' name='action' value='delete'>Slet Film</button>
                        </form>
                    </div>";
                }
            } else {
                echo "<p>Ingen film tilgængelige.</p>";
            }
            ?>
        </div>
    </section>

    <!-- Opret ny film -->
    <section id="create-movie">
        <h2>Opret Ny Film</h2>
        <form action="admin_movie.php" method="post" enctype="multipart/form-data">
            <label for="title">Titel:</label>
            <input type="text" id="title" name="title" required>

            <label for="director">Instruktør:</label>
            <input type="text" id="director" name="director" required>

            <label for="release_year">Udgivelsesår:</label>
            <input type="number" id="release_year" name="release_year" required>

            <label for="runtime">Varighed (i minutter):</label>
            <input type="number" id="runtime" name="runtime" required>

            <label for="age_limit">Aldersgrænse:</label>
            <input type="text" id="age_limit" name="age_limit" required>

            <label for="description">Beskrivelse:</label>
            <textarea id="description" name="description" rows="5" required></textarea>

            <label for="poster">Filmplakat:</label>
            <input type="file" id="poster" name="poster" accept="image/*" required>

            <button type="submit" name="action" value="create">Opret Film</button>
        </form>
    </section>
</div>

<script>
    // Funktion til at filtrere film baseret på søgeinput
    function filterMovies() {
        const searchValue = document.getElementById('search').value.toLowerCase();
        const movies = document.querySelectorAll('.movie-item');

        movies.forEach(movie => {
            const title = movie.getAttribute('data-title').toLowerCase();
            if (title.includes(searchValue)) {
                movie.style.display = 'flex';
            } else {
                movie.style.display = 'none';
            }
        });
    }
</script>

</body>
</html>


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
    width: 60px;
    height: auto;
    border-radius: 4px;
}

.search-bar {
    margin-bottom: 20px;
}

.search-bar input[type="text"] {
    padding: 8px;
    width: calc(100% - 20px);
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

/* Stil for flexbox til at organisere opret og eksisterende film sektionerne */
.movie-admin-container {
    display: flex;
    gap: 20px;
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
} 
</style>