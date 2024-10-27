<!-- header_admin.php -->
<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sektion - Drive-In Biograf</title>

    <!-- Dynamisk CSS indlæsning -->
    <?php
    if (!isset($pageLoader)) {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/app/services/PageLoader.php';
        $pageLoader = new PageLoader();
    }
    $page = $_GET['page'] ?? 'dashboard';
    $pageLoader->loadCss($page);  // Tilføj CSS for den valgte side
    ?>

    <link rel="stylesheet" href="/Exam-1sem-bio/app/assets/css/variables.css">
</head>

<body>
    <header>
        <nav>
            <ul>
                <li><a href="?page=dashboard">Dashboard</a></li>
                <li><a href="?page=settings">Administrer Sider</a></li>
                <li><a href="?page=admin_movie">CRUD Operationer</a></li>
                <li><a href="?page=manage_user">Brugeradministration</a></li>
                <li><a href="?page=settings">Indstillinger</a></li>
                <li><a href="?page=logout">Log Ud</a></li>
            </ul>
        </nav>
    </header>
</body>
</html>