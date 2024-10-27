<?php
// Router.php - Routing ansvar for bruger- og admin-sider
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/config/connection.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/core/autoloader.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/app/controllers/pageController.php';

class Router {
    public static function route($page) {
        // Tjek om brugeren er logget ind og om de er admin
        $isAdmin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
        $controller = new PageController();

        switch ($page) {
            // User Pages
            case 'homePage':
            case 'program':
            case 'movie':
            case 'about':
                $controller->loadUserPage($page);
                break;

            // Admin Pages - tjek at brugeren er admin før visning
            case 'dashboard':
            case 'admin_movie':
            case 'manage_pages':
            case 'manage_user':
            case 'settings':
                if ($isAdmin) {
                    $controller->loadAdminPage($page);
                } else {
                    header("Location: /Exam-1sem-bio/index.php?page=login");
                    exit;
                }
                break;

            // Default handling - vis startsiden, hvis siden ikke findes
            default:
                $controller->loadUserPage('home');
                break;
        }
    }
}
?>