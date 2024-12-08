<?php 
class AdminShowingsController {
    private $model;

    public function __construct($db) {
        $this->model = new AdminShowingsModel($db);
    }

    public function handleRequest($action) {
        switch ($action) {
            case 'list':
                return $this->index();  // Returner visninger
            case 'add':
                return $this->add();  // Håndter tilføjelse af visning
            case 'edit':
                return $this->edit();  // Håndter redigering af visning
            case 'delete':
                return $this->delete();  // Håndter sletning af visning
            default:
                // Håndter, hvis en ukendt action er anmodet
                header("HTTP/1.0 404 Not Found");
                echo "Action not found!";
                exit;
        }
    }
    
    

    public function index() {
        $showings = $this->model->getAllShowings();
        $movies = $this->model->getAllMovies();
        return ['showings' => $showings, 'movies' => $movies];
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $movieId = $_POST['movie_id'];
            $showingTime = $_POST['showing_time'];
            $screen = $_POST['screen'];

            if ($this->model->addShowing($movieId, $showingTime, $screen)) {
                header('Location: ?page=admin_daily_showings&success=true');
                exit;
            } else {
                // Fejlbehandling
                return ['error' => 'Fejl ved tilføjelse af visning'];
            }
        }
    }

    public function edit() {
        $showingId = $_GET['showing_id'];
        $showing = $this->model->getShowingById($showingId);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $movieId = $_POST['movie_id'];
            $showingTime = $_POST['showing_time'];
            $screen = $_POST['screen'];

            if ($this->model->updateShowing($showingId, $movieId, $showingTime, $screen)) {
                header('Location: ?page=admin_daily_showings&success=true');
                exit;
            } else {
                // Fejlbehandling
                return ['error' => 'Fejl ved opdatering af visning'];
            }
        }

        return ['showing' => $showing];
    }

    public function delete() {
        $showingId = $_GET['showing_id'];
        if ($this->model->deleteShowing($showingId)) {
            header('Location: ?page=admin_daily_showings&deleted=true');
            exit;
        } else {
            return ['error' => 'Fejl ved sletning af visning'];
        }
    }
}
