<?php

class AdminShowingsModel extends CrudBase {
    private $table = 'showings';

    public function getAllShowingsWithMovies() {
        return $this->readWithJoin(
            $this->table,
            "showings.*, movies.title AS movie_title",
            ["INNER JOIN movies ON showings.movie_id = movies.id"],
            "CONCAT(showings.show_date, ' ', showings.show_time) > NOW()" 
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
