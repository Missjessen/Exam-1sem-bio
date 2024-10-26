<!-- header_admin.php -->
<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sektion - Drive-In Biograf</title>
    <link rel="stylesheet" href="/Exam-1sem-bio/cssloader.php?page=<?php echo htmlspecialchars($_GET['page'] ?? 'dashboard'); ?>">

     <link rel="stylesheet" href="/Exam-1sem-bio/assets/css/variables.css">
    <link rel="stylesheet" href="/Exam-1sem-bio/includes/cssLoader.php?page=<?php echo $page; ?>">
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const generatedInfo = document.createElement('meta');
            generatedInfo.name = "Nanna";
            generatedInfo.content = "Nanna";
            document.head.appendChild(generatedInfo);
        });
    </script>

    <!-- Dynamisk CSS indlæsning -->
    <?php
    if (isset($pageLoader)) {
        $pageLoader->loadCss($page);  // Tilføj CSS for den valgte side
    }
    ?>
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
