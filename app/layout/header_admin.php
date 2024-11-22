<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/init.php'; // Inkluderer init.php med autoloader og db

?>

<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $current_page === 'admin_movie' ? 'Admin Movie - Drive-In Biograf' : 'Admin Sektion - Drive-In Biograf' ?></title>
    <link rel="stylesheet" href="/Exam-1sem-bio/assets/css/variables.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
   
</head>

<body>
    <header>
        <nav>
    <ul>
        <li class="<?= $current_page === 'admin_dashboard' ? 'active' : '' ?>"><a href="/Exam-1sem-bio/index.php?page=admin_dashboard">Dashboard</a></li>
        <li class="<?= $current_page === 'admin_movie' ? 'active' : '' ?>"><a href="/Exam-1sem-bio/index.php?page=admin_movie">Admin Movie</a></li>
        <li class="<?= $current_page === 'admin_ManageUsers' ? 'active' : '' ?>"><a href="/Exam-1sem-bio/index.php?page=admin_ManageUsers">Brugeradministration</a></li>
        <li class="<?= $current_page === 'admin_settings' ? 'active' : '' ?>"><a href="/Exam-1sem-bio/index.php?page=admin_settings">Indstillinger</a></li>
        <li><a href="/Exam-1sem-bio/index.php?page=admin_logout">Log Ud</a></li>
    </ul>
</nav>
    </header>
    </body>

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
            background-color: #f4f4f4;
            color: #333;
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

        nav ul li a:hover {
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
        }
        </style>