<?php 
require_once dirname(__DIR__, 2) . '/init.php';



// Hent den aktuelle side
$current_page = $_REQUEST['page'] ?? 'homePage';

// Indlæs konfigurationen
$config = require dirname(__DIR__) . '/config/config.php';

// Find den korrekte CSS-fil
$cssPath = $config['base_url'] . ($config['pages'][$current_page]['css'] ?? $config['default_css']);
?>

<!DOCTYPE html>
<html lang="da">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>  
        <?php 
              
           if ($current_page === 'movie_details' && $current_slug) {
               echo ucfirst(str_replace('-', ' ', $current_slug)) . ' - Drive-In Biograf';
           } else {
               echo ucfirst($current_page) . ' - Drive-In Biograf';
           }
           
        ?>
    </title>
    <link rel="stylesheet" href="/assets/css/variables.css">
    <link rel="stylesheet" href="<?= $cssPath ?>">
    <?php if ($current_page === 'movie_details') : ?>
        <link rel="stylesheet" href="/assets/css/movie_details.css">
</head>
<body>
<header>
    <nav>
        <div>Cruise Nights Cinema</div>
        <ul>
            <li class="<?= $current_page === 'homePage' ? 'active' : '' ?>"><a href="?page=homePage">Hjem</a></li>
            <li class="<?= $current_page === 'program' ? 'active' : '' ?>"><a href="?page=program">Program</a></li>
            <li class="<?= $current_page === 'admin_dashboard' ? 'active' : '' ?>"><a href="?page=admin_dashboard">Admin</a></li>
            
        </ul>
        
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
