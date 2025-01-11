<?php 
class AdminShowingsController {
    private $crudBase;
    private $pageLoader;

    public function __construct($db) {
        $this->crudBase = new CrudBase($db);
        $this->pageLoader = new PageLoader($db);
    }

    public function handleAction() {
        // Slet udløbne visninger
        $this->deleteExpiredShowings();
    
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
            $editingShowing = $this->crudBase->getItem('showings', ['id' => $showingId]);
            if (!$editingShowing) {
                throw new Exception("Visningen blev ikke fundet for ID: $showingId");
            }
            $this->showAdminShowings($editingShowing);
            return;
        }
    
        $this->showAdminShowings();
    }
    
    
    

    public function showAdminShowings($editingShowing = null) {
       
        $showings = $this->crudBase->readWithJoin(
            'showings',
            'showings.*, movies.title AS movie_title',
            [
                'JOIN movies ON showings.movie_id = movies.id',
                "WHERE CONCAT(showings.show_date, ' ', showings.show_time) >= NOW()" // Kun fremtidige visninger
            ]
        );
    
        $movies = $this->crudBase->getAllItems('movies');
    
        $data = [
            'showings' => $showings,
            'movies' => $movies,
            'editingShowing' => $editingShowing
        ];
    
        $this->pageLoader->renderPage('admin_showings', $data, 'admin');
    }
    
    public function createShowing($data) {
        try {
            $this->crudBase->create('showings', [
                'movie_id' => $data['movie_id'],
                'screen' => $data['screen'],
                'show_date' => $data['show_date'],
                'show_time' => $data['show_time'],
                'total_spots' => 50,
                'available_spots' => 50,
            ]);
    
            error_log("Visning oprettet med ID: " . $data['id']);
        } catch (PDOException $e) {
            error_log("Fejl ved oprettelse af visning: " . $e->getMessage());
            throw new Exception("Kunne ikke oprette visning.");
        }
    }
    
    private function deleteShowing($showingId) {
        $this->crudBase->delete('showings', ['id' => $showingId]);
        $this->redirectToShowings();
    }
    
    private function redirectToShowings() {
        header("Location: index.php?page=admin_showings");
        exit;
    }
    
    
    

    public function updateShowing($data) {
        if (empty($data['id'])) {
            throw new Exception("ID mangler ved opdatering.");
        }
    
        $this->crudBase->update('showings', [
            'movie_id' => $data['movie_id'],
            'screen' => $data['screen'],
            'show_date' => $data['show_date'],
            'show_time' => $data['show_time']
        ], ['id' => $data['id']]);
    }
    
    
    private function deleteExpiredShowings() {
        error_log("Sletter udløbne visninger...");
        $showings = $this->crudBase->read(
            'showings',
            '*',
            ["show_date < NOW()"]
        );
        error_log("Visninger til sletning: " . print_r($showings, true));
    
        $this->crudBase->delete(
            'showings',
            ["show_date < NOW()"]
        );
    }
    
    
   
    
}
