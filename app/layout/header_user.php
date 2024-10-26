<!-- header_user.php -->
<!-- /includes/header.php -->

<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drive-In Bio</title>
    <link rel="stylesheet" href="/Exam-1sem-bio/assets/css/variables.css">
    <link rel="stylesheet" href="/Exam-1sem-bio/includes/cssLoader.php?page=<?php echo $page; ?>">

    <!-- Dynamisk CSS indlæsning -->
    <?php
    if (isset($pageLoader)) {
        $pageLoader->loadCss($page);  // Tilføj CSS for den valgte side
    }
    ?>
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
