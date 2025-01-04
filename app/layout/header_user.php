<?php 
require_once dirname(__DIR__, 2) . '/init.php';


$current_page = $_REQUEST['page'] ?? 'homePage';
$current_slug = $_REQUEST['slug'] ?? '';

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
    <?php
    // Dynamisk CSS for hver side
    if (!empty($current_page)) {
        echo "<link rel='stylesheet' href='/assets/css/{$current_page}.css'>";
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
            <li class="<?= $current_page === 'admin_dashboard' ? 'active' : '' ?>"><a href="?page=admin_dashboard">Admin</a></li>
            
        </ul>
        
        <?php if (isset($_SESSION['user_id'])): ?>
        <a href="index.php?page=profile">Profil</a>
        <a href="index.php?page=logout">Log ud</a>
    <?php else: ?>
        <a href="index.php?page=login">Log ind</a>
        <a href="index.php?page=register">Registrer</a>
    <?php endif; ?>


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

    .message {
    background-color: #d4edda;
    color: #155724;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #c3e6cb;
    border-radius: 5px;
    font-size: 0.9em;
}

.auth-links a {
    margin-right: 10px;
    text-decoration: none;
    color: #007bff;
}

.auth-links a:hover {
    text-decoration: underline;
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