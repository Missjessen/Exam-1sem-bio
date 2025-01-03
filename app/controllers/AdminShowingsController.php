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

        if ($action === 'create') {
            $this->createShowing($_POST);
        } elseif ($action === 'update') {
            $this->updateShowing($_POST);
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
        $this->crudBase->delete('showings', ['id' => $showingId]);
    }
}
