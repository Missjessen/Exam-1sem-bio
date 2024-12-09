<?php 
class AdminShowingsModel extends CrudBase {
    public function __construct($db) {
        parent::__construct($db);
    }

    public function addShowing($data) {
        return $this->create('showings', $data);
    }

    public function updateShowing($id, $data) {
        $where = ['id' => $id];
        return $this->update('showings', $data, $where);
    }
    

    public function getShowingById($id) {
        return $this->getItem('showings', ['id' => $id]);
    }

    public function getAllShowings() {
        $joins = ["JOIN movies m ON showings.movie_id = m.id"];
        return $this->readWithJoin(
            'showings', 
            'showings.*, m.title AS movie_title', 
            $joins
        );
    }

    public function getAllMovies() {
        return $this->getAllItems('movies');
    }
}
