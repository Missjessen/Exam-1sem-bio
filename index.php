<?php
// Inkluder headeren
include $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/includes/header.php';

// Indlæs den dynamiske side loader
include $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/includes/loadPage.php';


// Hent 'page' parameteren og indlæs den korrekte side dynamisk
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
echo "Aktuel side: " . htmlspecialchars($page) . "<br>";
?>

<main>
    <?php
    // Indlæs den ønskede side dynamisk
    loadPage($page);
    ?>
</main>

<?php
// Inkluder footer
include $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/includes/footer.php';
?>
</body>
</html>
