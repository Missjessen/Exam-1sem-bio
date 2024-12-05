<?php 
require_once 'init.php';

$current_page = $GLOBALS['current_page'] ?? 'homePage'; // Standard til 'homePage' hvis ikke defineret
?>

<!DOCTYPE html>
<html lang="da">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $current_page === 'homePage' ? 'Hjem - Drive-In Biograf' : ucfirst($current_page) . ' - Drive-In Biograf' ?></title>
    <link rel="stylesheet" href="/Exam-1sem-bio/assets/css/variables.css">
    <?php
    // Dynamisk CSS for hver side
    if (!empty($current_page)) {
        echo "<link rel='stylesheet' href='/Exam-1sem-bio/assets/css/{$current_page}.css'>";
    }
    ?>
</head>
<body>
<header>
    <nav>
        <ul>
            <li class="<?= $current_page === 'homePage' ? 'active' : '' ?>"><a href="?page=homePage">Hjem</a></li>
            <li class="<?= $current_page === 'program' ? 'active' : '' ?>"><a href="?page=program">Program</a></li>
            <li class="<?= $current_page === 'movie' ? 'active' : '' ?>"><a href="?page=movie">Film</a></li>
            <li class="<?= $current_page === 'spots' ? 'active' : '' ?>"><a href="?page=spots">Pladser</a></li>
            <li class="<?= $current_page === 'tickets' ? 'active' : '' ?>"><a href="?page=tickets">Billetter</a></li>
            
        </ul>
        <div class="header-menu">
            <a href="/login.php" class="<?= $current_page === 'login' ? 'active' : '' ?>">Login</a> | 
            <a href="/register.php" class="<?= $current_page === 'register' ? 'active' : '' ?>">Registrer</a>
        </div>
    </nav>
</header>
</body>
</html>

<style>
    /* Basic reset */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    /* Styling navbar */
    body {
        font-family: Arial, sans-serif;
        background-color: #2e2e2e;
        color: #fff;
    }

    header {
        background-color: #333;
        padding: 10px 20px;
    }

    nav ul {
        list-style-type: none;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    nav ul li {
        margin: 0 15px;
    }

    nav ul li a {
        color: white;
        text-decoration: none;
        font-size: 16px;
        padding: 8px 12px;
        border-radius: 4px;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    nav ul li a.active {
        background-color: #5cb85c;
        color: white;
    }

    nav ul li a:hover {
        background-color: #5cb85c;
        color: white;
    }

    .header-menu {
        margin-left: auto;
        display: flex;
        gap: 10px;
    }

    .header-menu a {
        color: white;
        text-decoration: none;
        font-size: 16px;
        padding: 8px 12px;
        border-radius: 4px;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .header-menu a.active {
        background-color: #5cb85c;
    }

    .header-menu a:hover {
        background-color: #5cb85c;
        color: white;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        nav ul {
            flex-direction: column;
        }

        nav ul li {
            margin: 10px 0;
        }

        .header-menu {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>
