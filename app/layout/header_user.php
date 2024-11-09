<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/init.php'; // Inkluder init.php med autoloader og db


// Eksempel for at initialisere PageLoader og hente CSS'en for en specifik side
//$pageLoader = new PageLoader($db); 

// Antag at `$page` hentes via en URL-parameter eller på anden vis

?>

<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Drive-In Biografsssssssgggggggg</title>
    <link rel="stylesheet" href="/Exam-1sem-bio/assets/css/variables.css">
    <link rel="stylesheet" href="/Exam-1sem-bio<?php echo $cssPath; ?>">
</head>

<body>
   
        <h1>Drive-In Bio</h1>
        <nav>
            <ul>
                <li><a href="/Exam-1sem-bio/index.php?page=homePage">Hjem</a></li>
                <li><a href="/Exam-1sem-bio/index.php?page=program">Program</a></li>
                <li><a href="/Exam-1sem-bio/index.php?page=movie">Film</a></li>
                <li><a href="/Exam-1sem-bio/index.php?page=spots">Pladser</a></li>
                <li><a href="/Exam-1sem-bio/index.php?page=tickets">Billetter</a></li>
                <li><a href="/Exam-1sem-bio/index.php?page=about">Om Os</a></li>
                <li><a href="/Exam-1sem-bio/index.php?page=admin_dashboard">Admin</a></li>
            </ul>
        </nav>
  