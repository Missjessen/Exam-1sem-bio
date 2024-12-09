<?php 
require_once dirname(__DIR__, 2) . '/init.php';


$current_page = $_REQUEST['page'] ?? 'homePage';
$current_slug = $_REQUEST['slug'] ?? '';

define('CURRENT_PAGE', $current_page);

echo CURRENT_PAGE;
?>

<!DOCTYPE html>
<html lang="da">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>   <title>
        <?php 
              
           if ($current_page === 'movie_details' && $current_slug) {
               echo ucfirst(str_replace('-', ' ', $current_slug)) . ' - Drive-In Biograf';
           } else {
               echo ucfirst($current_page) . ' - Drive-In Biograf';
           }
           
        ?>
    </title></title>
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
        <div>Cruise Nights Cinema</div>
        <ul>
            <li class="<?= $current_page === 'homePage' ? 'active' : '' ?>"><a href="?page=homePage">Hjem</a></li>
            <li class="<?= $current_page === 'program' ? 'active' : '' ?>"><a href="?page=program">Program</a></li>
            <li class="<?= $current_page === 'movie' ? 'active' : '' ?>"><a href="?page=movie">Film</a></li>
            <li class="<?= $current_page === 'tickets' ? 'active' : '' ?>"><a href="?page=tickets">Billetter</a></li>
            <li class="<?= $current_page === 'admin_dashboard' ? 'active' : '' ?>"><a href="?page=admin_dashboard">Admin</a></li>
            
        </ul>
        <div class="header-menu">
            <?php if (isset($_SESSION['user_logged_in']) || isset($_SESSION['admin_logged_in'])): ?>
                <a href="/logout.php" class="<?= $current_page === 'logout' ? 'active' : '' ?>">Logout</a>
                <?php if (isset($_SESSION['admin_logged_in'])): ?>
                    | <a href="/admin.php" class="<?= $current_page === 'admin' ? 'active' : '' ?>">Admin</a>
                <?php endif; ?>
            <?php else: ?>
                <a href="/login.php" class="<?= $current_page === 'login' ? 'active' : '' ?>">Login</a> | 
                <a href="/register.php" class="<?= $current_page === 'register' ? 'active' : '' ?>">Registrer</a>
            <?php endif; ?>
        </div>
    </nav>
</header>
</body>
</html>

<style>

html, body {
    background-color: #000 !important;
    color: #f6f6f6; /* Tekstfarve */
    height: 100%; /* Sikrer, at baggrund dækker hele vinduet */
    margin: 0;
    padding: 0;
}
    /* Basic reset */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        background-color: #000;
    }

    /* Styling navbar */
    body {
        font-family: Arial, sans-serif;
       
        color: #f6f6f6;
    }

    header {
        background-color: #000;
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
        color: #f6f6f6;
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
        background-color: brown;
        color: white;
    }

    .header-menu {
        margin-left: auto;
        display: flex;
        gap: 10px;
    }

    .header-menu a {
        color: #f6f6f6;
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
