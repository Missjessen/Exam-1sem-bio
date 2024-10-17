<?php

// Forbind til databasen
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/includes/connection.php';

// Forbered og udfør forespørgslen

$stmt = $db->prepare("SELECT * FROM pages WHERE LOWER(page_name) = LOWER(:page)");
$stmt->bindParam(':page', $page, PDO::PARAM_STR);
$stmt->execute();
$pageData = $stmt->fetch(PDO::FETCH_ASSOC);

// Kontrollér, om siden blev fundet i databasen
if ($pageData) {
    // Indlæs sidens CSS, titel, og indhold
    echo '<link rel="stylesheet" href="/Exam-1sem-bio/assets/css/' . $pageData['css_file'] . '">';
    echo '<title>' . htmlspecialchars($pageData['title']) . '</title>';
    echo '<div>' . htmlspecialchars($pageData['content']) . '</div>';

    // Inkluder skabelonfilen, hvis den findes
    $templatePath = $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/' . $pageData['template_file'];
    if (file_exists($templatePath)) {
        include $templatePath;
    } else {
        echo "Skabelonfilen blev ikke fundet.";
    }
} else {
    // Vis fejlmeddelelse, hvis siden ikke blev fundet i databasen
    echo "Siden blev ikke fundet.";
}


?>
