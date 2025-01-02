<?php 

class AdminShowingsController {
    private $model;
    private $pageLoader;

    public function __construct($db) {
        $this->model = new CrudBase($db);
        $this->pageLoader = new PageLoader($db);
    }

    public function handleAction() {
        $action = $_GET['action'] ?? null;

        switch ($action) {
            case 'create':
                $this->handleCreate();
                break;
            case 'update':
                $this->handleUpdate();
                break;
            case 'delete':
                $this->handleDelete();
                break;
            default:
                $this->handleIndex();
                break;
        }
    }

    private function handleCreate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;

            // Validering kan tilføjes her
            if ($this->model->create('showings', $data)) {
                header("Location: index.php?page=admin_daily_showings");
                exit();
            } else {
                throw new Exception("Kunne ikke oprette filmvisning.");
            }
        } else {
            $this->pageLoader->renderPage('admin_showings_create', [], 'admin');
        }
    }

    private function handleUpdate() {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            throw new Exception("ID mangler for opdatering.");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;

            // Validering kan tilføjes her
            if ($this->model->update('showings', $data, ['id' => $id])) {
                header("Location: index.php?page=admin_daily_showings");
                exit();
            } else {
                throw new Exception("Kunne ikke opdatere filmvisning.");
            }
        } else {
            $showing = $this->model->getItem('showings', ['id' => $id]);
            $this->pageLoader->renderPage('admin_showings_update', ['showing' => $showing], 'admin');
        }
    }

    private function handleDelete() {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            throw new Exception("ID mangler for sletning.");
        }

        if ($this->model->delete('showings', ['id' => $id])) {
            header("Location: index.php?page=admin_daily_showings");
            exit();
        } else {
            throw new Exception("Kunne ikke slette filmvisning.");
        }
    }

    private function handleIndex() {
        $showings = $this->model->getAllItems('showings');
        $this->pageLoader->renderPage('admin_showings', ['showings' => $showings], 'admin');
    }
}
