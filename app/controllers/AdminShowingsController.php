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

        switch ($action) {
            case 'create':
                $this->createShowing($_POST);
                break;
            case 'update':
                $this->updateShowing($_POST);
                break;
            case 'delete':
                $this->deleteShowing($showingId);
                break;
            case 'edit':
                $this->editShowing($showingId);
                return;
            default:
                $this->showAdminShowings();
                break;
        }
    }

    private function showAdminShowings($editingShowing = null) {
        $showings = $this->crudBase->readWithJoin(
            'showings',
            'showings.*, movies.title AS movie_title',
            [
                'JOIN movies ON showings.movie_id = movies.id',
                "WHERE CONCAT(showings.show_date, ' ', showings.show_time) >= NOW()"
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

    private function createShowing($data) {
        try {
            $this->crudBase->create('showings', [
                'movie_id' => $data['movie_id'],
                'screen' => $data['screen'],
                'show_date' => $data['show_date'],
                'show_time' => $data['show_time'],
                'total_spots' => $data['total_spots'] ?? 50,
                'available_spots' => $data['available_spots'] ?? 50
            ]);

            header("Location: index.php?page=admin_showings&success=create");
            exit();
        } catch (PDOException $e) {
            error_log("Fejl ved oprettelse af visning: " . $e->getMessage());
            throw new Exception("Kunne ikke oprette visning.");
        }
    }

    private function updateShowing($data) {
        if (empty($data['id'])) {
            throw new Exception("ID mangler ved opdatering.");
        }

        try {
            $this->crudBase->update('showings', [
                'movie_id' => $data['movie_id'],
                'screen' => $data['screen'],
                'show_date' => $data['show_date'],
                'show_time' => $data['show_time'],
                'total_spots' => $data['total_spots'],
                'available_spots' => $data['available_spots']
            ], ['id' => $data['id']]);

            header("Location: index.php?page=admin_showings&success=update");
            exit();
        } catch (PDOException $e) {
            error_log("Fejl ved opdatering af visning: " . $e->getMessage());
            throw new Exception("Kunne ikke opdatere visning.");
        }
    }

    private function deleteShowing($showingId) {
        if (empty($showingId)) {
            throw new Exception("ID mangler ved sletning.");
        }

        try {
            $this->crudBase->delete('showings', ['id' => $showingId]);
            header("Location: index.php?page=admin_showings&success=delete");
            exit();
        } catch (PDOException $e) {
            error_log("Fejl ved sletning af visning: " . $e->getMessage());
            throw new Exception("Kunne ikke slette visning.");
        }
    }

    private function editShowing($showingId) {
        if (empty($showingId)) {
            throw new Exception("ID mangler ved redigering.");
        }

        $editingShowing = $this->crudBase->getItem('showings', ['id' => $showingId]);
        if (!$editingShowing) {
            throw new Exception("Visning med ID: $showingId blev ikke fundet.");
        }

        $this->showAdminShowings($editingShowing);
    }

    private function deleteExpiredShowings() {
        try {
            $this->crudBase->delete('showings', "CONCAT(show_date, ' ', show_time) < NOW()");
        } catch (PDOException $e) {
            error_log("Fejl ved sletning af udløbne visninger: " . $e->getMessage());
        }
    }
}
