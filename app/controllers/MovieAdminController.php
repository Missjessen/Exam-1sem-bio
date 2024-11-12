<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/init.php';

class MovieAdminController {
    private $movieModel;
    private $fileUploadService;

    public function __construct($db) {
        $this->movieModel = new MovieAdminModel($db);
        $this->fileUploadService = new FileUploadService(); // Initialiser FileUploadService
    }

    // Opret en ny film
    public function createMovie($data, $file, $actorIds = [], $genreIds = [], $newActors = '', $newGenres = '') {
        // Generér UUID og slug
        $movieId = $this->generateUUID();
        $data['id'] = $movieId;
        $data['slug'] = $this->generateSlug($data['title']);

        // Håndter upload af poster
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $data['poster'] = $this->fileUploadService->uploadFile($file);
        }

        // Opret filmen
        $this->movieModel->createMovie($data);

        // Tilføj nye skuespillere og genrer til databasen, hvis der er nogen
        $newActorIds = $this->addNewActors($newActors);
        $newGenreIds = $this->addNewGenres($newGenres);

        // Kombiner nye og eksisterende ids
        $allActorIds = array_merge($actorIds, $newActorIds);
        $allGenreIds = array_merge($genreIds, $newGenreIds);

        // Tilføj alle skuespillere og genrer til filmen
        $this->movieModel->addActorsToMovie($movieId, $allActorIds);
        $this->movieModel->addGenresToMovie($movieId, $allGenreIds);
    }

    // Opdater eksisterende film
    public function updateMovie($id, $data, $file = null, $actorIds = [], $genreIds = [], $newActors = '', $newGenres = '') {
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $data['poster'] = $this->fileUploadService->uploadFile($file);
        }

        if (isset($data['title'])) {
            $data['slug'] = $this->generateSlug($data['title']);
        }

        $this->movieModel->updateMovie($id, $data);

        // Clear existing actors and genres and add new ones
        $this->movieModel->clearActorsByMovie($id);
        $this->movieModel->clearGenresByMovie($id);

        // Add new actors and genres if provided
        $newActorIds = $this->addNewActors($newActors);
        $newGenreIds = $this->addNewGenres($newGenres);

        // Combine existing and new IDs
        $allActorIds = array_merge($actorIds, $newActorIds);
        $allGenreIds = array_merge($genreIds, $newGenreIds);

        // Assign actors and genres to the movie
        $this->movieModel->addActorsToMovie($id, $allActorIds);
        $this->movieModel->addGenresToMovie($id, $allGenreIds);
    }

     // Slet en film
     public function deleteMovie($movieId) {
        return $this->movieModel->deleteMovie($movieId);
    }

    public function getAllMovies() {
        return $this->movieModel->getAllMovies();
    }
    public function getMovie($id) {
        return $this->movieModel->getMovie($id);
    }
    public function getGenresByMovie($movieId) {
        return $this->movieModel->getGenresByMovie($movieId);
    }

    public function getActorsByMovie($movieId) {
        return $this->movieModel->getActorsByMovie($movieId);
    }

    public function getAllActors() {
        return $this->movieModel->getAllActors();
    }

    public function getAllGenres() {
        return $this->movieModel->getAllGenres();
    }

    private function generateUUID() {
        return bin2hex(random_bytes(16));
    }

    private function generateSlug($title) {
        $slug = strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $title));
        return trim($slug, '-');
    }

    private function addNewActors($newActors) {
        $newActorIds = [];
        if (!empty($newActors)) {
            $actorNames = explode(',', $newActors);
            foreach ($actorNames as $name) {
                $newActorIds[] = $this->movieModel->createActor(trim($name));
            }
        }
        return $newActorIds;
    }

    private function addNewGenres($newGenres) {
        $newGenreIds = [];
        if (!empty($newGenres)) {
            $genreNames = explode(',', $newGenres);
            foreach ($genreNames as $name) {
                $newGenreIds[] = $this->movieModel->createGenre(trim($name));
            }
        }
        return $newGenreIds;
    }

}
