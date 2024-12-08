<?php
require_once dirname(__DIR__, 3) . '/init.php';

// Testdatabaseforbindelse
try {
    $db = Database::getInstance()->getConnection();
} catch (Exception $e) {
    die("Fejl: Kunne ikke forbinde til databasen. " . $e->getMessage());
}

// Test slug-parameter
if (!isset($_GET['slug'])) {
    die("Fejl: Slug-parameteren mangler. Prøv URL som ?slug=the-dark-knight-2008");
}

// Hent slug fra URL
$slug = $_GET['slug'];

// Test databaseforespørgsel
try {
    $stmt = $db->prepare("SELECT * FROM movies WHERE slug = :slug");
    $stmt->execute(['slug' => $slug]);
    $movie = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$movie) {
        die("Fejl: Ingen film fundet med slug '$slug'.");
    }
} catch (Exception $e) {
    die("Fejl: Kunne ikke hente data fra databasen. " . $e->getMessage());
}

$stmt = $db->prepare("SELECT * FROM movies WHERE slug = :slug");
$stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
$stmt->execute();
$movie = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$movie) {
    throw new Exception("Filmen med slug '{$slug}' blev ikke fundet.");
}


// Visning af data
?>
<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Movie Details</title>
    <a href="?page=movie_details&slug=<?= urlencode($movie['slug'] ?? '') ?>">
    <?= htmlspecialchars($movie['title']) ?>
</a>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .movie-details {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .movie-details img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }
        .movie-details h1 {
            margin: 0 0 20px;
        }
        .movie-details p {
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="movie-details">
        <h1><?= htmlspecialchars($movie['title']) ?></h1>
        <img src="<?= htmlspecialchars($movie['poster']) ?>" alt="Poster for <?= htmlspecialchars($movie['title']) ?>">
        <p><strong>Beskrivelse:</strong> <?= htmlspecialchars($movie['description']) ?></p>
        <p><strong>Udgivelsesår:</strong> <?= htmlspecialchars($movie['release_year']) ?></p>
        <p><strong>Instruktør:</strong> <?= htmlspecialchars($movie['director']) ?></p>
        <p><strong>Sprog:</strong> <?= htmlspecialchars($movie['language']) ?></p>
        <p><strong>Premiere dato:</strong> <?= htmlspecialchars($movie['premiere_date']) ?></p>
    </div>
</body>
</html>
