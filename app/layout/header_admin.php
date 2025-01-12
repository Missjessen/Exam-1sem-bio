<?php 
require_once dirname(__DIR__, 2) . '/init.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Sørg for at beskytte alle admin-sider
if (!isset($_SESSION['admin_id'])) {
    if (headers_sent()) {
        echo "<script>window.location.href = '" . BASE_URL . "index.php?page=admin_login';</script>";
    } else {
        header("Location: " . BASE_URL . "index.php?page=admin_login");
        exit();
    }
}

$current_page = $_REQUEST['page'] ?? 'admin_dashboard';
?>

<header>
    <nav>
        <div>Cruise Nights Cinema</div>
        <ul>
            <li class="<?= $current_page === 'admin_dashboard' ? 'active' : '' ?>">
                <a href="<?= BASE_URL ?>index.php?page=admin_dashboard">Dashboard</a>
            </li>
            <li class="<?= $current_page === 'admin_bookings' ? 'active' : '' ?>">
                <a href="<?= BASE_URL ?>index.php?page=admin_bookings">Bookings</a>
            </li>
            <li class="<?= $current_page === 'admin_showings' ? 'active' : '' ?>">
                <a href="<?= BASE_URL ?>index.php?page=admin_showings">Showings</a>
            </li>
            <li class="<?= $current_page === 'admin_movie' ? 'active' : '' ?>">
                <a href="<?= BASE_URL ?>index.php?page=admin_movie">Movie Upload</a>
            </li>
            <li class="<?= $current_page === 'admin_ManageUsers' ? 'active' : '' ?>">
                <a href="<?= BASE_URL ?>index.php?page=admin_ManageUsers">Manage User</a>
            </li>
            <li class="<?= $current_page === 'admin_settings' ? 'active' : '' ?>">
                <a href="<?= BASE_URL ?>index.php?page=admin_settings">Info Indstillinger</a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>index.php?page=admin_logout">Log ud</a>
            </li>
        </ul>
    </nav>
</header>


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