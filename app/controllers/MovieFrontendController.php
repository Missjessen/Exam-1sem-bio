<?php

class MovieFrontendController {
    private $model;

    public function __construct(MovieFrontendModel $model) {
        $this->model = $model;
    }

    public function showMovieDetails($uuid) {
        $movie = $this->model->getMovieByUuid($uuid);
        if (!$movie) {
            header("HTTP/1.0 404 Not Found");
            require_once __DIR__ . '/../view/errors/404.php';
            exit();
        }

        require_once __DIR__ . '/../view/user/movieDetails.php';
    }
}

