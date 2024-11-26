<?php 
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/init.php';
$adminController = new AdminController(new AdminModel(Database::getInstance()->getConnection()));
$settings = $adminController->handleSettings();
echo "<pre>";
print_r($settings);
echo "</pre>";