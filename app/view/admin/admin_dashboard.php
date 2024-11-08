<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/init.php'; // Inkluder init.php med $db og autoloader

// Kontrollér, om brugeren er logget ind som admin
//if (!isset($_SESSION['admin_logged_in'])) {
   //header("Location: admin_login.php");
    //exit;
//}
?>

<!-- side menu -->
<div class="dashboard">
    <aside class="sidebar">
        <h2>Admin Panel</h2>
        <nav>
            <ul>
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><a href="admin_ManageUsers.php">Profiler</a></li>
                <li><a href="admin_movie.php">Movies</a></li>
                <li><a href="admin_booking.php">Booking</a></li>
                <li><a href="admin_parking.php">Spots</a></li>
                <li><a href="admin_settings.php">Info Indstillinger</a></li>
            </ul>
        </nav>
    </aside>
    
<!-- content admin dashboard -->
    <div class="main-content">
        <header class="topbar">
            <p>Velkommen, Admin</p>
            <a href="logout.php" class="logout">Log ud</a>
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
<style>
    /* General Styles for Admin Dashboard */
    body {
    background-color: #121212;
    color: #ffffff;
    font-family: 'Roboto', sans-serif;
    margin: 0;
    padding: 0;
}

.dashboard {
    display: flex;
    height: 100vh;
}

.sidebar {
    width: 250px;
    background: #1f1f1f;
    padding: 20px;
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.5);
}

.sidebar h2 {
    color: #ffb74d;
    margin-bottom: 30px;
}

.sidebar nav ul {
    list-style-type: none;
    padding: 0;
}

.sidebar nav ul li {
    margin: 15px 0;
}

.sidebar nav ul li a {
    color: #e0e0e0;
    text-decoration: none;
    font-size: 1.1em;
    padding: 10px;
    display: block;
    border-radius: 5px;
    transition: background 0.3s;
}

.sidebar nav ul li a:hover {
    background: #424242;
    color: #ffb74d;
}

.main-content {
    flex-grow: 1;
    padding: 30px;
    background-color: #181818;
}

.topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 20px;
    border-bottom: 1px solid #2c2c2c;
}

.topbar p {
    color: #ffb74d;
    font-size: 1.2em;
}

.topbar .logout {
    color: #ff5252;
    text-decoration: none;
    font-weight: bold;
    transition: color 0.3s;
}

.topbar .logout:hover {
    color: #ff867c;
}

.content {
    margin-top: 35px;
}

.cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.card {
    background: #1f1f1f;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    transition: transform 0.3s, box-shadow 0.3s;
}

.card:hover {
    transform: translateY(-10px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4);
}

.card h2 {
    color: #ffb74d;
    margin-bottom: 15px;
}

.card p {
    color: #e0e0e0;
    margin-bottom: 15px;
}

.card a {
    color: #42a5f5;
    text-decoration: none;
    font-weight: bold;
    transition: color 0.3s;
}

.card a:hover {
    color: #90caf9;
}

</style>