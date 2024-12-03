<?php
class MovieAdminController {
    private $MovieAdminModel;
    private $fileUploadService;
    private $pageLoader;

     public function __construct($db) {
        $this->MovieAdminModel = new MovieAdminModel($db);
        $this->fileUploadService = new FileUploadService();
        $this->pageLoader = new PageLoader($db); 
    }
    public function getAllMoviesWithDetails() {
        return $this->MovieAdminModel->getAllMoviesWithDetails(); // Kalder modelens metode
    }
    public function index() {
        try {
            $movies = $this->MovieAdminModel->getAllMoviesWithDetails();
            $actors = $this->MovieAdminModel->getAllActors();
            $genres = $this->MovieAdminModel->getAllGenres();

            $movieToEdit = $this->prepareMovieEdit();

            $movieToEdit = null;
            if (isset($_GET['action']) && $_GET['action'] === 'edit') {
                $movieId = $_GET['movie_id'] ?? null;
                if ($movieId) {
                    $movieToEdit = $this->MovieAdminModel->getMovieDetails($movieId);
                }
            }
    
            // Send data til view
            $this->pageLoader->loadAdminPage('admin_movie', compact('movies', 'actors', 'genres', 'movieToEdit'));
        } catch (Exception $e) {
            error_log("Fejl i MovieAdminController::index(): " . $e->getMessage());
            $this->pageLoader->loadErrorPage("Noget gik galt under indlæsningen af filmsiden.");
        }
    }
    
    
    //Håndterer POST-requests baseret på action.
    public function handlePostRequest() {
        if (!isset($_POST['action'])) {
            error_log("Ingen 'action' parameter blev sendt med POST-forespørgslen.");
            return; // Afslut tidligt, hvis 'action' ikke er sat
        }
    
        $action = $_POST['action'];
    
        switch ($action) {

            case 'edit':
                $movieId = $_POST['movie_id'] ?? null;
                if ($movieId) {
                    // Hent filmdata og forbered til redigering
                    $movieToEdit = $this->MovieAdminModel->getMovieDetails($movieId);
                    $this->pageLoader->loadAdminPage('admin_movie', [
                        'movieToEdit' => $movieToEdit,
                        'movies' => $this->MovieAdminModel->getAllMoviesWithDetails(),
                        'actors' => $this->MovieAdminModel->getAllActors(),
                        'genres' => $this->MovieAdminModel->getAllGenres(),
                    ]);
                    return; // Stop yderligere handling
                } else {
                    error_log("Ingen movie_id fundet til edit.");
                }
                break;
                
            case 'create_actor':
                $actorName = trim($_POST['actor_name'] ?? '');
                if ($actorName) {
                    $this->MovieAdminModel->createActor($actorName);
                } else {
                    error_log("Actor name mangler i 'create_actor' handling.");
                }
                break;
    
            case 'create_genre':
                $genreName = trim($_POST['genre_name'] ?? '');
                if ($genreName) {
                    $this->MovieAdminModel->createGenre($genreName);
                } else {
                    error_log("Genre name mangler i 'create_genre' handling.");
                }
                break;
    
            case 'create':
            case 'update':
                $this->handleMovieSave($action);
                break;
    
            case 'delete':
                $movieId = $_POST['movie_id'] ?? null;
                if ($movieId) {
                    $this->MovieAdminModel->deleteMovieWithRelations($movieId);
                } else {
                    error_log("Ingen movie_id blev sendt til 'delete' handling.");
                }
                break;
    
            default:
                error_log("Ukendt handling: $action");
        }
    
        // Redirect tilbage til admin_movie siden
        header("Location: /Exam-1sem-bio/index.php?page=admin_movie");
        exit;
    }

        
    
    // Gemmer eller opdaterer en film.
     
        private function handleMovieSave($action) {
            // Hent movie_id fra POST-data
            $movieId = $_POST['movie_id'] ?? null;
        
            // Saml filmdata fra formularen
            $movieData = [
                'title' => $_POST['title'] ?? '',
                'release_year' => $_POST['release_year'] ?? '',
                'length' => $_POST['length'] ?? '',
                'director' => $_POST['director'] ?? '',
                'description' => $_POST['description'] ?? '',
                'premiere_date' => $_POST['premiere_date'] ?? '',
                'language' => $_POST['language'] ?? '',
                'age_limit' => $_POST['age_limit'] ?? '',
                'status' => $_POST['status'] ?? '',

            ];

             // Generer UUID og slug for nye film
                if ($action === 'create') {
                    $movieData['id'] = $this->generateUUID();
                    $movieData['slug'] = $this->generateSlug($movieData['title']);
                }
        
            // Håndter filupload, hvis en plakat er vedhæftet
            if (isset($_FILES['poster']) && $_FILES['poster']['error'] === UPLOAD_ERR_OK) {
                $movieData['poster'] = $this->fileUploadService->uploadFile($_FILES['poster']);
            }
        
            // Hent relationer (actor_ids og genre_ids) fra POST-data
            $actorIds = $_POST['actor_ids'] ?? [];
            $genreIds = $_POST['genre_ids'] ?? [];
        
            // Håndter oprettelse og opdatering af film
            if ($action === 'update' && $movieId) {
                // Opdater eksisterende film
                $movieData['id'] = $movieId; // Sørg for at inkludere ID til opdatering
                $this->MovieAdminModel->updateMovie($movieId, $movieData);
        
                // Administrer relationer til skuespillere og genrer
                $this->MovieAdminModel->manageMovieActors($movieId, $actorIds);
                $this->MovieAdminModel->manageMovieGenres($movieId, $genreIds);
        
            } elseif ($action === 'create') {
                // Opret ny film
                $movieData['id'] = $this->generateUUID(); // Generer unikt ID
                $this->MovieAdminModel->createMovie($movieData);
        
                // Administrer relationer til skuespillere og genrer
                $this->MovieAdminModel->manageMovieActors($movieData['id'], $actorIds);
                $this->MovieAdminModel->manageMovieGenres($movieData['id'], $genreIds);
            } else {
                // Håndter ukendt handling eller manglende movie_id
                error_log("Ukendt handling eller manglende movie_id for handlingen: $action");
                throw new Exception("Ugyldig handling eller manglende movie_id");
            }
        }

    private function prepareMovieEdit() {
        if (isset($_GET['action']) && $_GET['action'] === 'edit') {
            $movieId = $_GET['movie_id'] ?? null;
            if ($movieId) {
                $movieDetails = $this->MovieAdminModel->getMovieDetails($movieId);
                error_log("Movie Details: " . print_r($movieDetails, true)); // Debug
                return $movieDetails;
            }
        }
        return null;
    }

    

    /**
     * Sletter en film og dens relationer.
     */
    public function deleteMovie($movieId) {
        if ($movieId) {
            $this->MovieAdminModel->deleteMovieWithRelations($movieId);
        }
    }

    /**
     * Genererer en UUID for nye poster.
     */
    private function generateUUID() {
        return bin2hex(random_bytes(16));
    }

    /**
     * Genererer en slug baseret på titel.
     */
    private function generateSlug($title) {
        return trim(strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)), '-');
    }

    /**
     * Håndterer oprettelse af nye aktører eller genrer.
     */
    private function processNewEntities($newEntities, $type) {
        $newIds = [];
        if (!empty($newEntities)) {
            $names = explode(',', $newEntities);
            foreach ($names as $name) {
                $newIds[] = $type === 'actor'
                    ? $this->MovieAdminModel->createActor(trim($name))
                    : $this->MovieAdminModel->createGenre(trim($name));
            }
        }
        return $newIds;
    }

    /**
     * Gemmer eller opdaterer en film og dens relationer.
     */
    public function saveMovie($data, $file, $actorIds, $genreIds, $newActors, $newGenres, $isUpdate) {
        try {
            if (empty($data['title'])) {
                throw new Exception("Film titel mangler.");
            }
    
            if (!$isUpdate) {
                $data['id'] = $this->generateUUID();
            }
    
            $data['slug'] = $this->generateSlug($data['title']);
    
            if ($file && $file['error'] === UPLOAD_ERR_OK) {
                $data['poster'] = $this->fileUploadService->uploadFile($file);
            }
    
            if ($isUpdate) {
                $this->MovieAdminModel->updateMovie($data['id'], $data);
            } else {
                $this->MovieAdminModel->createMovie($data);
            }
    
            $newActorIds = $this->processNewEntities($newActors, 'actor');
            $newGenreIds = $this->processNewEntities($newGenres, 'genre');
    
            $allActorIds = array_merge($actorIds, $newActorIds);
            $allGenreIds = array_merge($genreIds, $newGenreIds);
    
            $this->MovieAdminModel->manageMovieActors($data['id'], $allActorIds);
            $this->MovieAdminModel->manageMovieGenres($data['id'], $allGenreIds);
        } catch (Exception $e) {
            error_log("Fejl ved filmhåndtering: " . $e->getMessage());
        }
    }
    
    public function getAllActors() {
        return $this->MovieAdminModel->getAllActors(); // Kalder modelens metode
    }
    public function getAllGenres() {
        return $this->MovieAdminModel->getAllGenres(); // Kalder modelens metode
    }
    public function getMovieDetails($movieId) {
        return $this->MovieAdminModel->getMovieDetails($movieId); // Kalder modelens metode
    }
     
    
    
}