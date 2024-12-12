<?php

require_once dirname(__DIR__, 2) . '/init.php';

class Router {
    
    public static function route($page) {
        try {
            // Opret en ny instans af PageController
            $pageController = new PageController();
            
            // Brug dynamisk sidehåndtering
            $pageController->showPage($page);
        } catch (Exception $e) {
            // Log fejl og vis en generisk fejlbesked
            error_log("Router fejl: " . $e->getMessage());
            
            // Fallback til en fejlside
            $errorController = new ErrorController();
            $errorController->show404("Page not found: $page");
        }
    }

    public static function getPageURL($page) {
        $baseUrl = '/'; // Justér basestien for dit projekt
        $loadPages = require dirname(__DIR__) . '/config/loadPages.php';

        if (!isset($loadPages['pages'][$page])) {
            return $baseUrl . '?page=404'; // Fallback til 404
        }

        return $baseUrl . '?page=' . $page;
    }
}
