<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/init.php'; // Inkluderer init.php med autoloader og db

?>

<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sektion - Drive-In Biograf</title>
    <link rel="stylesheet" href="/Exam-1sem-bio/assets/css/variables.css">
    <link rel="stylesheet" href="/Exam-1sem-bio' . $cssPath . '">
</head>

<body>
    <header>
        <nav>
            <ul>
            <li><a href="/Exam-1sem-bio/index.php?page=admin_dashboard">Dashboard</a></li>
                <li><a href="/Exam-1sem-bio/index.php?page=admin_movie">Admin Movie</a></li>
                <li><a href="/Exam-1sem-bio/index.php?page=admin_ManageUser">Brugeradministration</a></li>
                <li><a href="/Exam-1sem-bio/index.php?page=admin_settings">Indstillinger</a></li>
                <li><a href="/Exam-1sem-bio/index.php?page=admin_logout">Log Ud</a></li>
            </ul>
        </nav>
    </header>
