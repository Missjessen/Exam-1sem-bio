<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/init.php';

class MovieAdminController {
    private $movieAdminModel;
    private $fileUploadService;
    private $pageLoader;

    public function __construct($db) {
        $this->movieAdminModel = new MovieAdminModel($db);
        $this->fileUploadService = new FileUploadService();
        $this->pageLoader = new PageLoader($db);
    }
    
    public function index() {
        // Håndter POST-anmodninger
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? null;
    
            if ($action === 'create_actor') {
                $actorName = htmlspecialchars($_POST['actor_name']);
                $this->createActor($actorName);
            } elseif ($action === 'create_genre') {
                $genreName = htmlspecialchars($_POST['genre_name']);
                $this->createGenre($genreName);
            } elseif ($action === 'create' || $action === 'update') {
                $data = [
                    'id' => $_POST['movie_id'] ?? null,
                    'title' => htmlspecialchars($_POST['title']),
                    'director' => htmlspecialchars($_POST['director']),
                    'release_year' => htmlspecialchars($_POST['release_year']),
                    'length' => htmlspecialchars($_POST['length']),
                    'age_limit' => htmlspecialchars($_POST['age_limit']),
                    'description' => htmlspecialchars($_POST['description']),
                    'premiere_date' => htmlspecialchars($_POST['premiere_date']),
                    'language' => htmlspecialchars($_POST['language']),
                ];
                $file = $_FILES['poster'] ?? null;
                $actorIds = $_POST['actor_ids'] ?? [];
                $genreIds = $_POST['genre_ids'] ?? [];
    
                if ($action === 'create') {
                    $this->saveMovie($data, $file, $actorIds, $genreIds, false);
                } elseif ($action === 'update') {
                    $this->saveMovie($data, $file, $actorIds, $genreIds, true);
                }
            } elseif ($action === 'delete') {
                $movieId = $_POST['movie_id'] ?? null;
                if ($movieId) {
                    $this->deleteMovie($movieId);
                }
            }
    
            // Omdiriger tilbage til admin_movie-siden
            header("Location: /Exam-1sem-bio/index.php?page=admin_movie");
            exit;
        }
    
        // Hent data til visningen
        $movies = $this->getAllMovies();
        $actors = $this->getAllActors();
        $genres = $this->getAllGenres();
    
        // Hvis der skal redigeres en film
        $movieToEdit = null;
        if (isset($_POST['action']) && $_POST['action'] === 'edit') {
            $movieId = $_POST['movie_id'] ?? null;
            if ($movieId) {
                $movieToEdit = $this->getMovieDetails($movieId);
            }
        }
    
        // Lever data til PageLoader
        $this->pageLoader->loadAdminPage('admin_movie', [
            'movies' => $movies,
            'actors' => $actors,
            'genres' => $genres,
            'movieToEdit' => $movieToEdit,
        ]);
    }
    
     // Opret eller opdater en film (fælles metode)
    public function saveMovie($data, $file = null, $actorIds = [], $genreIds = [], $newActors = '', $newGenres = '', $isUpdate = false) {
        try {
            // Generér UUID og slug kun for oprettelse
            if (!$isUpdate) {
                $data['id'] = $this->generateUUID();
            }

            if (isset($data['title'])) {
                $data['slug'] = $this->generateSlug($data['title']);
            }

            // Håndter upload af poster
            if ($file && $file['error'] === UPLOAD_ERR_OK) {
                $data['poster'] = $this->fileUploadService->uploadFile($file);
            }

            // Gem eller opdater filmen i databasen
            if ($isUpdate) {
                $this->movieAdminModel->updateMovie($data['id'], $data);
            } else {
                $this->movieAdminModel->createMovie($data);
            }

            // Behandl nye skuespillere og genrer
            $newActorIds = $this->processNewEntities($newActors, 'actor');
            $newGenreIds = $this->processNewEntities($newGenres, 'genre');

            // Kombiner eksisterende og nye IDs
            $allActorIds = array_merge($actorIds, $newActorIds);
            $allGenreIds = array_merge($genreIds, $newGenreIds);

            // Opdater relationer
            $this->movieAdminModel->manageMovieActors($data['id'], $allActorIds);
            $this->movieAdminModel->manageMovieGenres($data['id'], $allGenreIds);
        } catch (Exception $e) {
            error_log("Fejl ved " . ($isUpdate ? "opdatering" : "oprettelse") . " af film: " . $e->getMessage());
        }
    }

    // Slet en film
    public function deleteMovie($movieId) {
        try {
            // Fjern tilknytninger i mellem-tabellerne
            $this->movieAdminModel->deleteRelations('movie_genre', $movieId);
            $this->movieAdminModel->deleteRelations('movie_actor', $movieId);
    
            // Slet selve filmen
            $this->movieAdminModel->deleteMovie($movieId);
        } catch (Exception $e) {
            error_log("Fejl ved sletning af film: " . $e->getMessage());
        }
    }
   

    // Hent detaljer om en film
    public function getMovieDetails($movieId) {
        try {
            $movie = $this->movieAdminModel->getMovie($movieId);
            if ($movie) {
                $movie['actors'] = $this->movieAdminModel->getActorsByMovie($movieId);
                $movie['genres'] = $this->movieAdminModel->getGenresByMovie($movieId);
            }
            return $movie;
        } catch (Exception $e) {
            error_log("Fejl ved hentning af filmdetaljer: " . $e->getMessage());
            return null;
        }
    }

    public function getAllMoviesWithDetails() {
        try {
            // Hent alle film
            $movies = $this->movieAdminModel->getAllMovies();
    
            // Tilføj detaljer til hver film
            foreach ($movies as &$movie) {
                $movie['genres'] = $this->movieAdminModel->getGenresByMovie($movie['id']);
                $movie['actors'] = $this->movieAdminModel->getActorsByMovie($movie['id']);
            }
    
            return $movies;
        } catch (Exception $e) {
            error_log("Fejl ved hentning af alle film med detaljer: " . $e->getMessage());
            return [];
        }
    }
    

    public function getGenresByMovie($movieId) {
        return $this->movieAdminModel->getGenresByMovie($movieId); // Henter genrer fra modellen
    }
    
    public function getActorsByMovie($movieId) {
        return $this->movieAdminModel->getActorsByMovie($movieId); // Henter skuespillere fra modellen
    }
    

    // Hent alle skuespillere
    public function getAllActors() {
        return $this->movieAdminModel->getAllActors();
    }

    // Hent alle genrer
    public function getAllGenres() {
        return $this->movieAdminModel->getAllGenres();
    }

    // Hjælpefunktioner
    private function generateUUID() {
        return bin2hex(random_bytes(16));
    }

    private function generateSlug($title) {
        return trim(strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)), '-');
    }

    private function processNewEntities($newEntities, $type) {
        $newIds = [];
        if (!empty($newEntities)) {
            $names = explode(',', $newEntities);
            foreach ($names as $name) {
                if ($type === 'actor') {
                    $newIds[] = $this->movieAdminModel->createActor(trim($name));
                } elseif ($type === 'genre') {
                    $newIds[] = $this->movieAdminModel->createGenre(trim($name));
                }
            }
        }
        return $newIds;
    }

    public function getAllMovies() {
        $movies = $this->movieAdminModel->getAllMovies();
        return $movies;
    }

    public function createActor($name) {
        try {
            $this->movieAdminModel->createActor($name);
        } catch (Exception $e) {
            error_log("Fejl ved tilføjelse af skuespiller: " . $e->getMessage());
        }
    }
    
    public function createGenre($name) {
        try {
            $this->movieAdminModel->createGenre($name);
        } catch (Exception $e) {
            error_log("Fejl ved tilføjelse af genre: " . $e->getMessage());
        }
    }

    
    
    
}
