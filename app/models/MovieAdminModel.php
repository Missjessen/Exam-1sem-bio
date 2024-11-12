<?php
class MovieAdminModel extends CrudBase {
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
        return $this->delete('movies', ['id' => $movieId]);
    }

    public function getAllMovies() {
        return $this->read('movies');
    }

    public function getMovie($movieId) {
        return $this->read('movies', '*', ['id' => $movieId], true);
    }

    public function getActorsByMovie($movieId) {
        $stmt = $this->db->prepare("SELECT a.name FROM actors a INNER JOIN movie_actor ma ON a.id = ma.actor_id WHERE ma.movie_id = :movie_id");
        $stmt->execute(['movie_id' => $movieId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getGenresByMovie($movieId) {
        $stmt = $this->db->prepare("SELECT g.name FROM genres g INNER JOIN movie_genre mg ON g.id = mg.genre_id WHERE mg.movie_id = :movie_id");
        $stmt->execute(['movie_id' => $movieId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // Tilføj skuespillere til en film
    public function addActorsToMovie($movieId, $actorIds) {
        foreach ($actorIds as $actorId) {
            $stmt = $this->db->prepare("INSERT INTO movie_actor (movie_id, actor_id) VALUES (:movie_id, :actor_id)");
            $stmt->execute(['movie_id' => $movieId, 'actor_id' => $actorId]);
        }
    }

    // Tilføj genrer til en film
    public function addGenresToMovie($movieId, $genreIds) {
        foreach ($genreIds as $genreId) {
            $stmt = $this->db->prepare("INSERT INTO movie_genre (movie_id, genre_id) VALUES (:movie_id, :genre_id)");
            $stmt->execute(['movie_id' => $movieId, 'genre_id' => $genreId]);
        }
    }

    // Fjern alle skuespillere tilknyttet en film
    public function clearActorsByMovie($movieId) {
        $stmt = $this->db->prepare("DELETE FROM movie_actor WHERE movie_id = :movie_id");
        $stmt->execute(['movie_id' => $movieId]);
    }

    // Fjern alle genrer tilknyttet en film
    public function clearGenresByMovie($movieId) {
        $stmt = $this->db->prepare("DELETE FROM movie_genre WHERE movie_id = :movie_id");
        $stmt->execute(['movie_id' => $movieId]);
    }

    // Opret en ny skuespiller og returner id
    public function createActor($name) {
        $stmt = $this->db->prepare("INSERT INTO actors (name) VALUES (:name)");
        $stmt->execute(['name' => $name]);
        return $this->db->lastInsertId();
    }

    // Opret en ny genre og returner id
    public function createGenre($name) {
        $stmt = $this->db->prepare("INSERT INTO genres (name) VALUES (:name)");
        $stmt->execute(['name' => $name]);
        return $this->db->lastInsertId();
    }

    public function getAllActors() {
        $stmt = $this->db->query("SELECT * FROM actors");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllGenres() {
        $stmt = $this->db->query("SELECT * FROM genres");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

