<?php
require_once 'init.php';

class BaseController {
    protected $model;
    protected $errorController;


    public function __construct($model) {
        $this->model = $model;
        $this->errorController = new ErrorController();
    }

    protected function validateInput($input) {
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }

    
    protected function handleError($errorMessage, $statusCode = 500) {
        if ($statusCode === 404) {
            $this->errorController->show404($errorMessage);
        } else {
            $this->errorController->show500($errorMessage);
        }
    }
    
}



