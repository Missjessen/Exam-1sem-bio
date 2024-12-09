
<?php 
require_once dirname(__DIR__, 2) . '/init.php';

$current_page = $_REQUEST['page'] ?? 'admin_dashboard';

echo CURRENT_PAGE;


?>

<!DOCTYPE html>
<html lang="da">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $current_page === 'admin_settings' ? 'Admin Settings - Drive-In Biograf' : 'Admin Sektion - Drive-In Biograf' ?></title>
    <link rel="stylesheet" href="/Exam-1sem-bio/assets/css/variables.css">
    <link rel="stylesheet" href="<?php echo '/css/' . $current_page . '.css'; ?>">
   
</head>
<body>
<header>
    <nav>
        <ul>
            <li class="<?= $GLOBALS['current_page'] === 'admin_dashboard' ? 'active' : '' ?>"><a href="?page=admin_dashboard">Dashboard</a></li>
            <li class="<?= $GLOBALS['current_page'] === 'admin_bookings' ? 'active' : '' ?>"><a href="?page=admin_bookings">Admin Booking</a></li>
            <li class="<?= $GLOBALS['current_page'] === 'admin_daily_showings' ? 'active' : '' ?>"><a href="?page=admin_daily_showings">Showings</a></li>
            <li class="<?= $GLOBALS['current_page'] === 'admin_movie' ? 'active' : '' ?>"><a href="?page=admin_movie">Admin Movie</a></li>
            <li class="<?= $GLOBALS['current_page'] === 'admin_ManageUsers' ? 'active' : '' ?>"><a href="?page=admin_ManageUsers">Manage User</a></li>
            <li class="<?= $GLOBALS['current_page'] === 'admin_settings' ? 'active' : '' ?>"><a href="?page=admin_settings">Indstillinger</a></li>
            <li class="<?= $GLOBALS['current_page'] === 'admin_parking' ? 'active' : '' ?>"><a href="?page=admin_parking">Admin Parking</a></li>
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