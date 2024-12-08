
<?php 
require_once dirname(__DIR__, 2) . '/init.php';
$current_page = $_REQUEST['page'] ?? 'admin_dashboard';

echo CURRENT_PAGE;








?>

<header>
    <nav>
        <div>Cruise Nights Cinema</div>
        <ul>
            <li class="<?= $current_page === 'admin_dashboard' ? 'active' : '' ?>"><a href="?page=admin_dashboard">Dashboard</a></li>
            <li class="<?= $current_page === 'admin_bookings' ? 'active' : '' ?>"><a href="?admin_bookings">Bookings</a></li>
            <li class="<?= $current_page === 'admin_daily_showings' ? 'active' : '' ?>"><a href="?page=admin_daily_showings">Showings</a></li>
            <li class="<?= $current_page === 'admin_movie' ? 'active' : '' ?>"><a href="?page=admin_movie">Movie Upload</a></li>
            <li class="<?= $current_page === 'admin_ManageUsers' ? 'active' : '' ?>"><a href="?page=admin_ManageUsers">Manage User</a></li>
             <li class="<?= $current_page === 'admin_settings' ? 'active' : '' ?>"><a href="?page=admin_settings">Info Indstillinger</a></li>
            
            <?php if (isset($_SESSION['admin_logged_in'])): ?>
                <li class="<?= $current_page === 'admin_dashboard' ? 'active' : '' ?>">
                    <a href="?page=admin_dashboard">Admin Dashboard</a>
                </li>
            <?php endif; ?>
        </ul>
        <div class="header-menu">
            <?php if (isset($_SESSION['user_logged_in']) || isset($_SESSION['admin_logged_in'])): ?>
                <a href="/logout.php" class="<?= $current_page === 'logout' ? 'active' : '' ?>">Logout</a>
            <?php else: ?>
                <a href="/login.php" class="<?= $current_page === 'login' ? 'active' : '' ?>">Login</a> | 
                <a href="/register.php" class="<?= $current_page === 'register' ? 'active' : '' ?>">Registrer</a>
            <?php endif; ?>
        </div>
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