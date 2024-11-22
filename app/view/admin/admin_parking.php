<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/init.php';



$db = new PDO(DSN, DB_USER, DB_PASS);

$model = new MovieAdminModel($db);
$controller = new MovieAdminController($db);

$movies = $controller->getAllMovies();
print_r($movies);

