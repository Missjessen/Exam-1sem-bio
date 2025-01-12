<?php
class MovieAdminController {
    private $movieAdminModel;
    private $fileUploadService;
    private $pageLoader;

     public function __construct($db) {
        $this->movieAdminModel = new MovieAdminModel($db);
        $this->fileUploadService = new FileUploadService();
        $this->pageLoader = new PageLoader($db); 
    }
    public function getAllMoviesWithDetails() {
        return $this->movieAdminModel->getAllMoviesWithDetails(); // Kalder modelens metode
    }
    public function index() {
        try {
            $movies = $this->movieAdminModel->getAllMoviesWithDetails();
            $actors = $this->movieAdminModel->getAllActors();
            $genres = $this->movieAdminModel->getAllGenres();

            $movieToEdit = $this->prepareMovieEdit();

            if (isset($_GET['action']) && $_GET['action'] === 'edit') {
                $movieId = $_GET['movie_id'] ?? null;
                if ($movieId) {
                    $movieToEdit = $this->movieAdminModel->getMovieDetails($movieId);
                }
            }
    
            // Send data til view
            $this->pageLoader->loadAdminPage('admin_movie', compact('movies', 'actors', 'genres', 'movieToEdit'));
        } catch (Exception $e) {
            error_log("Fejl i MovieAdminController::index(): " . $e->getMessage());
            $this->pageLoader->renderErrorPage("Noget gik galt under indlæsningen af filmsiden.", "Error Message");
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
                    $movieToEdit = $this->movieAdminModel->getMovieDetails($movieId);
                    $this->pageLoader->loadAdminPage('admin_movie', [
                        'movieToEdit' => $movieToEdit,
                        'movies' => $this->movieAdminModel->getAllMoviesWithDetails(),
                        'actors' => $this->movieAdminModel->getAllActors(),
                        'genres' => $this->movieAdminModel->getAllGenres(),
                    ]);
                    return; // Stop yderligere handling
                } else {
                    error_log("Ingen movie_id fundet til edit.");
                }
                break;
                
            case 'create_actor':
    $actorName = trim($_POST['actor_name'] ?? '');
    $actorBirthdate = $_POST['actor_birthdate'] ?? null;

    if (empty($actorName) || empty($actorBirthdate)) {
        error_log("Manglende data: Skuespillerens navn eller fødselsdato.");
        $this->pageLoader->renderErrorPage(400, "Både navn og fødselsdato er påkrævet.");
        return;
    }

    try {
        $this->movieAdminModel->createActor($actorName, $actorBirthdate);
    } catch (Exception $e) {
        error_log("Fejl under oprettelse af aktør: " . $e->getMessage());
        $this->pageLoader->renderErrorPage(500, "Kunne ikke oprette skuespiller.");
    }
    break;
    
            case 'create_genre':
                $genreName = trim($_POST['genre_name'] ?? '');
                if ($genreName) {
                    $this->movieAdminModel->createGenre($genreName);
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
                    $this->movieAdminModel->deleteMovieWithRelations($movieId);
                } else {
                    error_log("Ingen movie_id blev sendt til 'delete' handling.");
                }
                break;
    
            default:
                error_log("Ukendt handling: $action");
        }
    
        // Redirect tilbage til admin_movie siden
        header("Location: " . BASE_URL . "index.php?page=admin_movie");
        exit;
        
    }

        
    
    // Gemmer eller opdaterer en film.
     
   private function handleMovieSave($action) {
    $allowedStatuses = ['available', 'archived', 'coming_soon']; // Tilladte ENUM-værdier

    $movieData = [
        'title' => $_POST['title'] ?? '',
        'release_year' => $_POST['release_year'] ?? '',
        'length' => $_POST['length'] ?? '',
        'director' => $_POST['director'] ?? '',
        'description' => $_POST['description'] ?? '',
        'premiere_date' => $_POST['premiere_date'] ?? '',
        'language' => $_POST['language'] ?? 'Engelsk',
        'age_limit' => $_POST['age_limit'] ?? 'U',
        'status' => in_array($_POST['status'] ?? '', $allowedStatuses) ? $_POST['status'] : 'available', // Valider status
        'slug' => $this->generateSlug($_POST['title'] ?? ''),
        'poster' => isset($_FILES['poster']) && $_FILES['poster']['error'] === UPLOAD_ERR_OK
            ? $this->fileUploadService->uploadFile($_FILES['poster'])
            : null
    ];

    if ($action === 'update' && isset($_POST['movie_id'])) {
        $this->movieAdminModel->updateMovie($_POST['movie_id'], $movieData);
    } elseif ($action === 'create') {
        $movieData['id'] = $this->generateUUID();
        $this->movieAdminModel->createMovie($movieData);
    } else {
        throw new Exception("Ugyldig handling eller manglende movie_id");
    }
}

    private function prepareMovieEdit() {
        if (isset($_GET['action']) && $_GET['action'] === 'edit') {
            $movieId = $_GET['movie_id'] ?? null;
            if ($movieId) {
                $movieDetails = $this->movieAdminModel->getMovieDetails($movieId);
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
            $this->movieAdminModel->deleteMovieWithRelations($movieId);
        }
    }

    /**
     * Genererer en UUID for nye poster.
     */
    public function generateUUID() {
        return bin2hex(random_bytes(16));
    }

    /**
     * Genererer en slug baseret på titel.
     */
    public function generateSlug($title) {
        // Fjern HTML-tags, hvis de findes
        $title = strip_tags($title);
    
        // Erstat mellemrum med bindestreger og fjern specialtegn
        return trim(strtolower(preg_replace('/[^a-zA-Z0-9-]+/', '-', $title)), '-');
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
                    ? $this->movieAdminModel->createActor(trim($name))
                    : $this->movieAdminModel->createGenre(trim($name));
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
                $this->movieAdminModel->updateMovie($data['id'], $data);
            } else {
                $this->movieAdminModel->createMovie($data);
            }
    
            $newActorIds = $this->processNewEntities($newActors, 'actor');
            $newGenreIds = $this->processNewEntities($newGenres, 'genre');
    
            $allActorIds = array_merge($actorIds, $newActorIds);
            $allGenreIds = array_merge($genreIds, $newGenreIds);
    
            $this->movieAdminModel->manageMovieActors($data['id'], $allActorIds);
            $this->movieAdminModel->manageMovieGenres($data['id'], $allGenreIds);
        } catch (Exception $e) {
            error_log("Fejl ved filmhåndtering: " . $e->getMessage());
        }
    }
    
    public function getAllActors() {
        return $this->movieAdminModel->getAllActors(); // Kalder modelens metode
    }
    public function getAllGenres() {
        return $this->movieAdminModel->getAllGenres(); // Kalder modelens metode
    }
    public function getMovieDetails($movieId) {
        return $this->movieAdminModel->getMovieDetails($movieId); // Kalder modelens metode
    }
     
    
    
}