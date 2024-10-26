<?php

// /core/BaseController.php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/core/baseModel.php';

class BaseController {
    protected $model;

    public function __construct($model) {
        $this->model = $model;
    }

    protected function validateInput($input) {
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }
}
?>
