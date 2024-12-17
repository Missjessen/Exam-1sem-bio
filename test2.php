<?php 
$adminModel = new AdminModel();
$admin = $adminModel->getAdminByEmail('admin@example.com');
var_dump($admin);