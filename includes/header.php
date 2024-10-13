<?php  
// Korrigér stien til filen
include $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/includes/functions.php'; 

require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/includes/connection.php';

// Sørg for, at $page bliver sat korrekt

// Definér tilladte sider og deres tilsvarende CSS-filer i et array
$cssFiles = [
    'spots' => '/css/spots.css',
    'movie' => '/css/movie.css',
    'haeder' => '/css/about.css',  // Hvis du har om os-siden
    'contact' => '/css/contact.css',  // Tilføj andre sider efter behov
    'services' => '/css/services.css',
    'gallery' => '/css/gallery.css'
];

// Hent $page fra URL'en, eller sæt den til null, hvis parameteren ikke findes
$page = $_GET['page'] ?? null;

// Vælg CSS-fil baseret på $page, eller brug common.css som fallback
$cssFile = $cssFiles[$page] ?? '/css/common.css';
?>





<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drive-In Bio</title>
    <link rel="stylesheet" href="/Exam-1sem-bio/css/variables.css">
    <?php echo '<link rel="stylesheet" href="/Exam-1sem-bio' . $cssFile . '">'; ?>
</head>
<body>
<body>
<header>
    <h1>Drive-In Bio</h1>
    <nav>
        <ul>
            <li><a href="?page=home">Hjem</a></li>
            <li><a href="?page=movie">Film</a></li>
            <li><a href="?page=spots">Pladser</a></li>
            <li><a href="?page=about">Om Os</a></li>
        </ul>
    </nav>
</header>

<?php
// Inkluder footer.php med en absolut sti
include $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/includes/footer.php';
?>
</body>
</html>
