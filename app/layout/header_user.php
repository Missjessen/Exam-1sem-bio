<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/init.php'; // Inkluderer init.php med autoloader og db
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/app/services/pageLoader.php';
  

?>

<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drive-In Bio</title>
    <link rel="stylesheet" href="/Exam-1sem-bio/assets/css/variables.css">
    <link rel="stylesheet" href="/Exam-1sem-bio' . $cssPath . '">
    <link rel="stylesheet" href="/Exam-1sem-bio/services/pageLoader.php?page=<?php echo $page; ?>"
</head>

<body>
    <header>
        <h1>Drive-In Bio</h1>
        <nav>
            <ul>
                <li><a href="/Exam-1sem-bio/index.php?page=homePage">Hjem</a></li>
                <li><a href="/Exam-1sem-bio/index.php?page=program">Program</a></li>
                <li><a href="/Exam-1sem-bio/index.php?page=movie">Film</a></li>
                <li><a href="/Exam-1sem-bio/index.php?page=spots">Pladser</a></li>
                <li><a href="/Exam-1sem-bio/index.php?page=tickets">Billetter</a></li>
                <li><a href="/Exam-1sem-bio/index.php?page=about">Om Os</a></li>
                <li><a href="/Exam-1sem-bio/index.php?page=admin">Admin</a></li>
            </ul>
        </nav>
    </header>
