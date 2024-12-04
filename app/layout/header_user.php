<?php
require_once BASE_PATH . '/init.php';


// Eksempel for at initialisere PageLoader og hente CSS'en for en specifik side
//$pageLoader = new PageLoader($db); 

// Antag at `$page` hentes via en URL-parameter eller på anden vis
if (isset($_GET['page']) && $_GET['page'] === 'admin_dashboard') {
    header("Location: /Exam-1sem-bio/index.php?page=admin_dashboard");
    exit();
}
?>


<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Drive-In Biograf</title>
    <link rel="stylesheet" href="/Exam-1sem-bio/assets/css/variables.css">
    <link rel="stylesheet" href="/Exam-1sem-bio<?php echo $cssPath; ?>">
</head>


<body>
   
        <h1>Drive-In Bio</h1>
        <nav>
            <ul>
                <li><a href="/Exam-1sem-bio/index.php?page=homePage">Hjem</a></li>
                <li><a href="/Exam-1sem-bio/index.php?page=program">Program</a></li>
                <li><a href="/Exam-1sem-bio/index.php?page=movie">Film</a></li>
                <li><a href="/Exam-1sem-bio/index.php?page=spots">Pladser</a></li>
                <li><a href="/Exam-1sem-bio/index.php?page=tickets">Billetter</a></li>
                <li><a href="/Exam-1sem-bio/index.php?page=about">Om Os</a></li>
                <li><a href="/Exam-1sem-bio/index.php?page=admin_dashboard">Admin</a></li>
            </ul>
        </nav>


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
