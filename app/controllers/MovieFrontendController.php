<?php

class MovieFrontendController {
    private $model;

    public function __construct($db) {
        $this->model = new MovieFrontendModel($db); // Sørg for at bruge den rigtige model
    }

 
    }




