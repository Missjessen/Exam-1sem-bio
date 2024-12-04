<?php
require_once 'init.php';
class AdminShowingModel extends CrudBase {
    public function getAllShowings() {
        $joins = [
            "INNER JOIN movies ON showings.movie_id = movies.id"
        ];
        return $this->readWithJoin(
            'showings',
            'showings.*, movies.title AS movie_title',
            $joins
        );
    }

    public function getShowingById($id) {
        return $this->read('showings', '*', ['id' => $id], true);
    }

    public function createShowing($data) {
        return $this->create('showings', $data);
    }

    public function updateShowing($id, $data) {
        return $this->update('showings', $data, ['id' => $id]);
    }

    public function deleteShowing($id) {
        return $this->delete('showings', ['id' => $id]);
    }

    // Funktion til at oprette gentagne showings
    public function createRepeatedShowings($data) {
        $startDate = new DateTime($data['show_date']);
        $endDate = new DateTime($data['repeat_until']);
        $interval = $data['repeat_pattern'] === 'weekly' ? 'P1W' : 'P1D';

        $dates = [];
        while ($startDate <= $endDate) {
            $dates[] = $startDate->format('Y-m-d');
            $startDate->add(new DateInterval($interval));
        }

        foreach ($dates as $date) {
            $data['show_date'] = $date;
            $this->createShowing($data);
        }
    }
}
