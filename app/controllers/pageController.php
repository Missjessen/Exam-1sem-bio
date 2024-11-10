<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/init.php';

class PageController {
    private $pageLoader;
    private $userController;
    private $adminController;

    public function __construct($db) {
        $this->pageLoader = new PageLoader($db);
        $this->userController = new UserController(new UserModel($db));
        $this->adminController = new AdminController(new AdminModel($db));
    }

    public function showPage($page) {
        if ($page === 'admin_movie') {
            $movies = $this->adminController->getAllMovies();
            $this->pageLoader->loadAdminPage($page, ['movies' => $movies]);
        } else {
            if (strpos($page, 'admin') === 0) {
                $this->pageLoader->loadAdminPage($page);
            } else {
                $this->pageLoader->loadUserPage($page);
            }
        }
    }

    public function showMoviePage($slug) {
        $this->userController->showMovie($slug);
    }

    public function addMovie() {
        $this->adminController->addMovie();
    }

    public function editMovie($movieId) {
        $this->adminController->editMovie($movieId);
    }
}
