<?php

class MovieFrontendController {
    private $model;

    public function __construct($db) {
        $this->model = new MovieFrontendModel($db); 
    }

 
    }




