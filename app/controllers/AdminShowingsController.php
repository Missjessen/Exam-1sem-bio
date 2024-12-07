<?php 
require_once dirname(__DIR__, 2) . '/init.php';


class AdminShowingsController {
    private $model;

    public function __construct($db) {
        $this->model = new AdminShowingsModel($db);
    }

    public function handleRequest($action) {
        switch ($action) {
            case 'add':
                $this->addShowing();
                break;
            case 'delete':
                $this->deleteShowing();
                break;
            default:
                return $this->listShowings();
        }
    }
    
    private function listShowings() {
        $movies = $this->model->getAllMovies() ?? [];
        $showings = $this->model->getAllShowings() ?? [];
    
        // Returnér et komplet array med alle forventede nøgler
        return [
            'movies' => $movies,
            'showings' => $showings,
        ];
    }
    

    private function addShowing() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $movieId = $_POST['movie_id'];
            $showingTime = $_POST['showing_time'];

            if ($this->model->addShowing($movieId, $showingTime)) {
                header('Location: ?page=admin_daily_showings&success=true');
                exit;
            } else {
                echo "Kunne ikke tilføje visning.";
            }
        }
    }

    private function deleteShowing() {
        $showingId = $_GET['showing_id'];

        if ($this->model->deleteShowing($showingId)) {
            header('Location: ?page=admin_daily_showings&deleted=true');
            exit;
        } else {
            echo "Kunne ikke slette visning.";
        }
    }
}
