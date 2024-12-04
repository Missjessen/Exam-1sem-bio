<?php 
require_once BASE_PATH . 'init.php';

class AdminShowingController {
    private $model;

    public function __construct(AdminShowingModel $model) {
        $this->model = $model;
    }

    public function getAllShowings() {
        return $this->model->getAllShowings();
    }

    public function getShowingById($id) {
        return $this->model->getShowingById($id);
    }

    public function saveShowing($data) {
        if (!empty($data['id'])) {
            return $this->model->updateShowing($data['id'], $data);
        } else {
            if (isset($data['repeat_pattern']) && $data['repeat_pattern'] !== 'none') {
                return $this->model->createRepeatedShowings($data);
            }
            return $this->model->createShowing($data);
        }
    }

    public function deleteShowing($id) {
        return $this->model->deleteShowing($id);
    }
}
