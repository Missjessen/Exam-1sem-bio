<?php

// /app/services/Router.php
class Router {
    public static function route($page) {
        // Tjek om brugeren er logget ind og om de er admin
        $isAdmin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';

        switch ($page) {
            // User Pages
            case 'homePage':
            case 'program':
            case 'movie':
            case 'spots':
            case 'tickets':
            case 'about':
                require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/app/controllers/PageController.php';
                $controller = new PageController();
                $controller->loadPage($page, 'user');
                break;

            // Admin Pages - tjek at brugeren er admin før visning
            case 'dashboard':
            case 'manage_pages':
            case 'admin_crud':
            case 'manage_user':
            case 'settings':
                if ($isAdmin) {
                    require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/app/controllers/pageController.php';
                    $controller = new pageController();
                    $controller->handle($page);
                } else {
                    header("Location: /Exam-1sem-bio/index.php?page=login");
                    exit;
                }
                break;

            // Default handling - vis startsiden, hvis siden ikke findes
            default:
                require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/app/controllers/PageController.php';
                $controller = new PageController();
                $controller->loadPage('homePage', 'user');
                break;
        }
    }
}
?>