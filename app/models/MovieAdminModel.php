<?php
class MovieAdminModel extends CrudBase {
    public function __construct() {
        parent::__construct(Database::getInstance()->getConnection());
    }

    // Opret en ny film
    public function createMovie($data) {
        return $this->create('movies', $data);
    }

    // Opdater en eksisterende film
    public function updateMovie($movieId, $data) {
        try {
            // Forbindelse til database fra singleton
            $db = Database::getInstance()->getConnection();
    
            // Bygger dynamisk SQL-sætning baseret på data
            $columns = [];
            foreach ($data as $key => $value) {
                $columns[] = "$key = :$key";
            }
    
            $sql = "UPDATE movies SET " . implode(', ', $columns) . " WHERE id = :id";
    
            $stmt = $db->prepare($sql); // Bruger singleton-forbindelsen
    
            // Binder værdier til SQL-parametrene
            foreach ($data as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
    
            $stmt->bindValue(":id", $movieId, PDO::PARAM_STR); // Binder movie ID
    
            $stmt->execute(); // Udfører opdateringen
    
            return $stmt->rowCount(); // Returnerer antallet af opdaterede rækker
        } catch (PDOException $e) {
            // Log fejl og kast en exception for fejlhåndtering
            error_log("Fejl ved opdatering af film med ID $movieId: " . $e->getMessage());
            throw new Exception("Kunne ikke opdatere filmen med ID $movieId.");
        }
    }
    

    // Slet en film
    public function deleteMovie($movieId) {
        return $this->delete('movies', ['id' => $movieId]);
    }

    // Hent alle film
    public function getAllMovies() {
        $sql = "SELECT * FROM movies";
        return $this->executeQuery($sql);
    }

    // Hent en specifik film
    public function getMovie($movieId) {
        return $this->read('movies', '*', ['id' => $movieId], true);
    }

    public function getAllMoviesWithDetails($limit = 10, $offset = 0) {
        $sql = "
        SELECT m.*, 
        GROUP_CONCAT(DISTINCT g.name) AS genres, 
        GROUP_CONCAT(DISTINCT a.name) AS actors
            FROM 
                movies m
            LEFT JOIN 
                movie_genre mg ON m.id = mg.movie_id
            LEFT JOIN 
                genres g ON mg.genre_id = g.id
            LEFT JOIN 
                movie_actor ma ON m.id = ma.movie_id
            LEFT JOIN 
                actors a ON ma.actor_id = a.id
            GROUP BY 
                m.id
            LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMovieDetails($movieId) {
        try {
            // Hent filmen
            $movie = $this->getMovie($movieId);
            if (!$movie) {
                error_log("Ingen film fundet for ID: $movieId");
                throw new Exception("Filmen med ID $movieId blev ikke fundet.");
            }
    
            // Hent genrer og skuespillere
            $movie['genres'] = $this->getGenresByMovie($movieId) ?: [];
            error_log("Genrer for film $movieId: " . print_r($movie['genres'], true));
            $movie['actors'] = $this->getActorsByMovie($movieId) ?: [];
            error_log("Skuespillere for film $movieId: " . print_r($movie['actors'], true));
    
            return $movie;
        } catch (Exception $e) {
            error_log("Fejl i getMovieDetails: " . $e->getMessage());
            throw $e; // Genkast undtagelsen for at håndtere den længere oppe
        }
    }
    

    // Hent genrer for en film
    public function getGenresByMovie($movieId) {
        $joins = [
            "INNER JOIN movie_genre mg ON genres.id = mg.genre_id"
        ];
        return $this->readWithJoin('genres', 'genres.id, genres.name', $joins, ['mg.movie_id' => $movieId]) ?: [];
    }

    // Hent skuespillere for en film
    public function getActorsByMovie($movieId) {
        $joins = [
            "INNER JOIN movie_actor ma ON actors.id = ma.actor_id"
        ];
        return $this->readWithJoin('actors', 'actors.id, actors.name', $joins, ['ma.movie_id' => $movieId]) ?: [];
    }

    // Hent alle aktører
    public function getAllActors() {
        $sql = "SELECT * FROM actors";
        return $this->executeQuery($sql);
    }

    // Hent alle genrer
    public function getAllGenres() {
        $sql = "SELECT * FROM genres";
        return $this->executeQuery($sql);
    }

    public function createActor($name) {
        $sql = "INSERT INTO actors (name) VALUES (:name)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['name' => $name]);
        return $this->db->lastInsertId();
    }
    
    public function createGenre($name) {
        $sql = "INSERT INTO genres (name) VALUES (:name)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['name' => $name]);
        return $this->db->lastInsertId();
    }


    // Tjek om aktør eksisterer
    public function getActorByName($name) {
        $sql = "SELECT id FROM actors WHERE name = :name";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['name' => $name]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['id'] ?? null;
    }

    // Tjek om genre eksisterer
    public function getGenreByName($name) {
        $sql = "SELECT id FROM genres WHERE name = :name";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['name' => $name]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['id'] ?? null;
    }

    // Administrer relationer mellem film og genrer
    public function manageMovieGenres($movieId, $genreIds) {
        $this->manageRelations($movieId, $genreIds, 'movie_genre', 'genre_id');
    }

    // Administrer relationer mellem film og skuespillere
    public function manageMovieActors($movieId, $actorIds) {
        $this->manageRelations($movieId, $actorIds, 'movie_actor', 'actor_id');
    }

    // Generisk funktion til at administrere relationer
    private function manageRelations($movieId, $entityIds, $relationTable, $entityColumn) {
        $this->db->beginTransaction();
        try {
            // Fjern eksisterende relationer
            $this->delete($relationTable, ['movie_id' => $movieId]);

            // Tilføj nye relationer
            foreach ($entityIds as $entityId) {
                $this->create($relationTable, ['movie_id' => $movieId, $entityColumn => $entityId]);
            }
            $this->db->commit();
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Fejl ved administration af relationer: " . $e->getMessage());
            throw new Exception("Kunne ikke administrere relationer.");
        }
    }

   /*  private function manageRelations($movieId, $entityIds, $relationTable, $entityColumn) {
        $this->delete($relationTable, ['movie_id' => $movieId]);
        foreach ($entityIds as $id) {
            $this->create($relationTable, ['movie_id' => $movieId, $entityColumn => $id]);
        }
    } */


    public function saveMovie($data, $actorIds, $genreIds, $newActors, $newGenres, $isUpdate) {
        if (!$isUpdate) {
            $data['id'] = bin2hex(random_bytes(16));
            $this->create('movies', $data);
        } else {
            $this->update('movies', $data, ['id' => $data['id']]);
        }

        $this->manageRelations($data['id'], $actorIds, 'movie_actor', 'actor_id');
        $this->manageRelations($data['id'], $genreIds, 'movie_genre', 'genre_id');
    }

    // Slet relationer til en film
    public function deleteRelations($relationTable, $movieId) {
        try {
            $sql = "DELETE FROM $relationTable WHERE movie_id = :movie_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':movie_id', $movieId, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            error_log("Fejl under sletning af relationer: " . $e->getMessage());
            throw $e;
        }
    }
    public function deleteMovieWithRelations($movieId) {
        $this->db->beginTransaction();
        try {
            // Slet relationer
            $this->deleteRelations('movie_genre', $movieId);
            $this->deleteRelations('movie_actor', $movieId);
    
            // Slet filmen
            $this->deleteMovie($movieId);
    
            $this->db->commit();
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Fejl i deleteMovieWithRelations: " . $e->getMessage());
            throw new Exception("Kunne ikke slette filmen med ID $movieId.");
        }
    }

    public function createActors(array $actorNames) {
        $actorIds = [];
        try {
            foreach ($actorNames as $name) {
                $name = trim($name);
                if (!empty($name)) {
                    $actorIds[] = $this->create('actors', ['name' => $name]);
                }
            }
            return $actorIds;
        } catch (PDOException $e) {
            error_log("Fejl ved oprettelse af aktører: " . $e->getMessage());
            throw new Exception("Kunne ikke oprette aktører.");
        }
    }
    
    // Opret flere genrer
    public function createGenres(array $genreNames) {
        $genreIds = [];
        try {
            foreach ($genreNames as $name) {
                $name = trim($name);
                if (!empty($name)) {
                    $genreIds[] = $this->create('genres', ['name' => $name]);
                }
            }
            return $genreIds;
        } catch (PDOException $e) {
            error_log("Fejl ved oprettelse af genrer: " . $e->getMessage());
            throw new Exception("Kunne ikke oprette genrer.");
        }
    }
    
    

    // Eksekver en SQL-forespørgsel
    private function executeQuery($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
