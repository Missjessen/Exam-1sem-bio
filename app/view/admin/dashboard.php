<?php
session_start();


require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/includes/functions.php';

// Kontrollér, om brugeren er logget ind som admin
/* if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
} */
?>

<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="dashboard">
        <!-- Sidebar -->
        <aside class="sidebar">
            <h2>Admin Panel</h2>
            <nav>
                <ul>
                <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="manage_pages.php"><i class="fas fa-file-alt"></i> Sider</a></li>
                <li><a href="manage_users.php"><i class="fas fa-users"></i> Brugere</a></li>
                <li><a href="manage_content.php"><i class="fas fa-edit"></i> Indhold</a></li>
                <li><a href="settings.php"><i class="fas fa-cogs"></i> Indstillinger</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Topbar -->
            <header class="topbar">
                <p>Velkommen, Admin</p>
                <a href="logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Log ud</a>
            </header>

            <div class="content">
                <h1>Dashboard</h1>
                <p>Velkommen til admin-dashboardet!</p>
                <div class="cards">
                    <div class="card">
                        <h2>Sider</h2>
                        <p>Administrer website sider</p>
                        <a href="manage_pages.php">Gå til sider</a>
                    </div>
                    <div class="card">
                        <h2>Brugere</h2>
                        <p>Administrer brugerkonti</p>
                        <a href="manage_users.php">Gå til brugere</a>
                    </div>
                    <div class="card">
                        <h2>Indhold</h2>
                        <p>Rediger website indhold</p>
                        <a href="manage_content.php">Gå til indhold</a>
                    </div>
                    <div class="card">
                        <h2>Indstillinger</h2>
                        <p>Opdater website indstillinger</p>
                        <a href="settings.php">Gå til indstillinger</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
