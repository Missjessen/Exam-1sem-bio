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
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($action === 'create') {
                $this->createShowing($_POST);
            } elseif ($action === 'update') {
                $this->updateShowing($_POST);
            }
        } elseif ($action === 'delete') {
            $this->deleteShowing($showingId);
        } elseif ($action === 'edit') {
            // Hent visningen for redigering
            $editingShowing = $this->crudBase->getItem('showings', ['id' => $showingId]);
            $this->showAdminShowings($editingShowing);
            return; // Sørg for, at vi ikke fortsætter til standardvisning
        }
    
        $this->showAdminShowings();
    }
    
    

    public function showAdminShowings($editingShowing = null) {
        $showings = $this->crudBase->readWithJoin(
            'showings',
            'showings.*, movies.title AS movie_title',
            ['JOIN movies ON showings.movie_id = movies.id']
        );
    
        $movies = $this->crudBase->getAllItems('movies');
    
        $data = [
            'showings' => $showings,
            'movies' => $movies,
            'editingShowing' => $editingShowing
        ];
    
        $this->pageLoader->renderPage('admin_showings', $data, 'admin');
    }
    
    private function createShowing($data) {
        $this->crudBase->create('showings', [
            'movie_id' => $data['movie_id'],
            'screen' => $data['screen'],
            'show_date' => $data['show_date'],
            'show_time' => $data['show_time'],
            'total_spots' => 50,
            'available_spots' => 50
        ]);
        $this->redirectToShowings();
    }
    
    private function deleteShowing($showingId) {
        $this->crudBase->delete('showings', ['id' => $showingId]);
        $this->redirectToShowings();
    }
    
    private function redirectToShowings() {
        header("Location: " . BASE_URL . "index.php?page=admin_showings");
        exit;
    }
    
    

    private function updateShowing($data) {
        $this->crudBase->update('showings', [
            'movie_id' => $data['movie_id'],
            'screen' => $data['screen'],
            'show_date' => $data['show_date'],
            'show_time' => $data['show_time']
        ], ['id' => $data['id']]);
    }

   
    
}
