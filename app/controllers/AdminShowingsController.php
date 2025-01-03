<?php 
class AdminShowingsController {
    private $crudBase;
    private $pageLoader;

    public function __construct($db) {
        $this->crudBase = new CrudBase($db);
        $this->pageLoader = new PageLoader($db);
    }

    public function handleAction() {
        $action = $_POST['action'] ?? $_GET['action'] ?? null;
        $showingId = $_POST['id'] ?? $_GET['showing_id'] ?? null;
    
        // Håndter kun POST-anmodninger
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($action === 'create') {
                $this->createShowing($_POST);
            } elseif ($action === 'update') {
                $this->updateShowing($_POST);
            }
        } elseif ($action === 'delete') {
            $this->deleteShowing($showingId);
        }
    
        $this->showAdminShowings();
    }
    

    private function showAdminShowings() {
        $showings = $this->crudBase->readWithJoin(
            'showings',
            'showings.*, movies.title AS movie_title',
            ['INNER JOIN movies ON showings.movie_id = movies.id']
        );
        $movies = $this->crudBase->getAllItems('movies');
        $editingShowing = isset($_GET['action']) && $_GET['action'] === 'edit'
            ? $this->crudBase->getItem('showings', ['id' => $_GET['showing_id']])
            : null;

        $this->pageLoader->renderPage('admin_showings', [
            'showings' => $showings,
            'movies' => $movies,
            'editingShowing' => $editingShowing
        ], 'admin');
    }

    private function createShowing($data) {
        error_log("Opretter visning med data: " . print_r($data, true)); // Debug-log
    
        $this->crudBase->create('showings', [
            'movie_id' => $data['movie_id'],
            'screen' => $data['screen'],
            'show_date' => $data['show_date'],
            'show_time' => $data['show_time'],
            'total_spots' => 50,
            'available_spots' => 50
        ]);
    }
    

    private function updateShowing($data) {
        $this->crudBase->update('showings', [
            'movie_id' => $data['movie_id'],
            'screen' => $data['screen'],
            'show_date' => $data['show_date'],
            'show_time' => $data['show_time']
        ], ['id' => $data['id']]);
    }

    private function deleteShowing($showingId) {
        if ($this->crudBase->delete('showings', ['id' => $showingId])) {
            error_log("Visning med ID $showingId blev slettet.");
        } else {
            error_log("Fejl ved sletning af visning med ID $showingId.");
        }
    }
    
}
