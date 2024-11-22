<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/init.php';
class MovieAdminModel extends CrudBase {



    public function __construct($db) {
        $this->db = $db;
    }

    // Opret en ny film
    public function createMovie($data) {
        return $this->create('movies', $data);
    }

    // Opdater en eksisterende film
    public function updateMovie($movieId, $data) {
        return $this->update('movies', $data, ['id' => $movieId]);
    }

    // Slet en film
    public function deleteMovie($movieId) {
        try {
            // Direkte sletning fra movies-tabellen; relationer håndteres af databasen
            return $this->delete('movies', ['id' => $movieId]);
        } catch (Exception $e) {
            error_log("Fejl ved sletning af film: " . $e->getMessage());
            return false;
        }
    }
    

    // Hent alle film
    public function getAllMovies() {
        try {
            $sql = "SELECT * FROM movies";
            $stmt = $this->db->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("Fetched movies: " . print_r($result, true)); // Debugging
            return $result;
        } catch (PDOException $e) {
            error_log("Fejl ved hentning af film: " . $e->getMessage());
            return [];
        }
    }
    


    // Hent en specifik film
    public function getMovie($movieId) {
        return $this->read('movies', '*', ['id' => $movieId], true);
    }

    // Generisk funktion til at hente relationer (skuespillere eller genrer)
    public function getRelatedEntities($movieId, $relationTable, $entityTable, $entityColumns) {
        $joins = [
            "INNER JOIN $relationTable ON $entityTable.id = $relationTable.{$entityTable}_id"
        ];
        return $this->readWithJoin($entityTable, $entityColumns, $joins, ["$relationTable.movie_id" => $movieId]);
    }

    // Hent skuespillere for en film
    public function getGenresByMovie($movieId) {
        $joins = [
            "INNER JOIN movie_genre mg ON genres.id = mg.genre_id"
        ];
        $result = $this->readWithJoin('genres', 'genres.id, genres.name', $joins, ['mg.movie_id' => $movieId]);
    
        return $result ?: []; // Returner en tom array, hvis der ikke er noget resultat
    }
    
    public function getActorsByMovie($movieId) {
        $joins = [
            "INNER JOIN movie_actor ma ON actors.id = ma.actor_id"
        ];
        $result = $this->readWithJoin('actors', 'actors.id, actors.name', $joins, ['ma.movie_id' => $movieId]);
    
        return $result ?: []; // Returner en tom array, hvis der ikke er noget resultat
    }
    

    // Administrer relationer mellem film og skuespillere/genrer
    private function manageRelations($movieId, $entityIds, $relationTable, $entityColumn) {
        // Hent eksisterende relationer for filmen
        $existingRelations = $this->read($relationTable, $entityColumn, ['movie_id' => $movieId]);
    
        // Konverter eksisterende relationer til en simpel liste
        $existingIds = array_column($existingRelations, $entityColumn);
    
        // Find relationer, der skal tilføjes
        $relationsToAdd = array_diff($entityIds, $existingIds);
    
        // Find relationer, der skal fjernes
        $relationsToRemove = array_diff($existingIds, $entityIds);
    
        // Slet kun de relationer, der ikke længere er nødvendige
        if (!empty($relationsToRemove)) {
            $placeholders = implode(',', array_fill(0, count($relationsToRemove), '?'));
            $sql = "DELETE FROM $relationTable WHERE movie_id = ? AND $entityColumn IN ($placeholders)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(array_merge([$movieId], $relationsToRemove));
        }
    
        // Tilføj kun de nye relationer
        if (!empty($relationsToAdd)) {
            $placeholders = implode(',', array_fill(0, count($relationsToAdd), '(?, ?)'));
            $sql = "INSERT INTO $relationTable (movie_id, $entityColumn) VALUES $placeholders";
            $stmt = $this->db->prepare($sql);
            $params = [];
            foreach ($relationsToAdd as $idToAdd) {
                $params[] = $movieId;
                $params[] = $idToAdd;
            }
            $stmt->execute($params);
        }
    }

    // Administrer skuespillere for en film
    public function manageMovieActors($movieId, $actorIds) {
        if (!empty($actorIds)) {
            $this->manageRelations($movieId, $actorIds, 'movie_actor', 'actor_id');
        }
    }
    
    // Administrer genrer for en film
    public function manageMovieGenres($movieId, $genreIds) {
        if (!empty($genreIds)) {
            $this->manageRelations($movieId, $genreIds, 'movie_genre', 'genre_id');
        }
    }

    // Opret en ny skuespiller og returner id
    public function createActor($name) {
        $sql = "INSERT INTO actors (name) VALUES (:name)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['name' => $name]);
        return $this->db->lastInsertId();
    }

    // Opret en ny genre og returner id
    public function createGenre($name) {
        $sql = "INSERT INTO genres (name) VALUES (:name)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['name' => $name]);
        return $this->db->lastInsertId();
    }

    // Hent alle skuespillere
    public function getAllActors() {
        try {
            $sql = "SELECT * FROM actors";
            $stmt = $this->db->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("Fetched actors: " . print_r($result, true)); // Debugging
            return $result;
        } catch (PDOException $e) {
            error_log("Fejl ved hentning af skuespillere: " . $e->getMessage());
            return [];
        }
    }
    

    // Hent alle genrer
    public function getAllGenres() {
        try {
            $sql = "SELECT * FROM genres";
            $stmt = $this->db->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("Fetched genres: " . print_r($result, true)); // Debugging
            return $result;
        } catch (PDOException $e) {
            error_log("Fejl ved hentning af genrer: " . $e->getMessage());
            return [];
        }
    }

    public function deleteRelations($relationTable, $movieId) {
        return $this->delete($relationTable, ['movie_id' => $movieId]);
    }
    
}