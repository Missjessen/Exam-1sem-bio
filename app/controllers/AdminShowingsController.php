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
                return $this->listShowtimes(); // Updated to fetch showtimes
        }
    }
    
    private function listShowtimes() {
        $movies = $this->model->getAllMovies(); // Fetch all movies
        $showings = $this->model->getAllShowings(); // Fetch all showtimes from the updated method
        return compact('movies', 'showings'); // Return data for the view
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
