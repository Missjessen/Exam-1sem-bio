<?php
require_once 'init.php';

class BaseController {
    protected $model;

    public function __construct($model) {
        $this->model = $model;
    }

    protected function validateInput($input) {
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }


    
}



