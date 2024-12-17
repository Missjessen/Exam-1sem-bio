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

    
}
