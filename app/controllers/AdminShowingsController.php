<?php 
class AdminShowingsController {
    private $model;

    public function __construct($db) {
        $this->model = new AdminShowingsModel($db);
    }

    public function handleRequest($action) {
        switch ($action) {
            case 'add':
                return $this->add();
            case 'edit':
                return $this->edit();
            case 'delete':
                return $this->delete();
            default:
                return $this->index();
        }
    }

    public function index() {
        $showings = $this->model->getAllShowings();
        $movies = $this->model->getAllMovies();
        return ['showings' => $showings, 'movies' => $movies];
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'movie_id' => $_POST['movie_id'] ?? null,
                'screen' => $_POST['screen'] ?? null,
                'show_date' => $_POST['show_date'] ?? null,
                'show_time' => $_POST['show_time'] ?? null,
                'total_spots' => $_POST['total_spots'] ?? null,
                'available_spots' => $_POST['available_spots'] ?? null,
                'repeat_pattern' => $_POST['repeat_pattern'] ?? 'none',
                'repeat_until' => $_POST['repeat_until'] ?? null,
            ];
            $data = array_filter($data, fn($value) => !is_null($value));
    
            if ($this->model->addShowing($data)) {
                header('Location: ?page=admin_daily_showings&success=true');
                exit;
            }
        }
    
        // Returner nødvendige data for at genindlæse siden
        return $this->index();
    }
    
    

    public function edit() {
        $id = $_GET['showing_id'] ?? null;
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'movie_id' => $_POST['movie_id'] ?? null,
                'screen' => $_POST['screen'] ?? null,
                'show_date' => $_POST['show_date'] ?? null,
                'show_time' => $_POST['show_time'] ?? null,
                'total_spots' => $_POST['total_spots'] ?? null,
                'available_spots' => $_POST['available_spots'] ?? null,
                'repeat_pattern' => $_POST['repeat_pattern'] ?? 'none',
                'repeat_until' => $_POST['repeat_until'] ?? null,
            ];
            $data = array_filter($data, fn($value) => !is_null($value));
    
            if ($this->model->updateShowing($id, $data)) {
                header('Location: ?page=admin_daily_showings&success=true');
                exit;
            }
        }
    
        // Returner nødvendige data for at genindlæse siden
        $showing = $this->model->getShowingById($id);
        $data = $this->index();
        $data['showing'] = $showing;
    
        return $data;
    }
    
    

    public function delete() {
        $id = $_GET['showing_id'] ?? null;
        if ($this->model->delete('showings', ['id' => $id])) {
            header('Location: ?page=admin_daily_showings&deleted=true');
            exit;
        }
    }
}

