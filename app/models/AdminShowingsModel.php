<?php

class AdminShowingsModel extends CrudBase {
    private $table = 'showings';

    public function getAllShowingsWithMovies() {
        // Brug readWithJoin til at hente showings sammen med filmdata
        return $this->readWithJoin(
            $this->table,
            "showings.*, movies.title AS movie_title",
            ["INNER JOIN movies ON showings.movie_id = movies.id"]
        );
    }

    public function createShowing($data) {
        return $this->create($this->table, $data);
    }

    public function updateShowing($id, $data) {
        return $this->update($this->table, $data, ['id' => $id]);
    }

    public function deleteShowing($id) {
        return $this->delete($this->table, ['id' => $id]);
    }
}
