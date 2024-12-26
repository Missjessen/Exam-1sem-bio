<?php

require_once dirname(__DIR__, 2) . '/init.php';

class Router {
    
    public static function route($page) {
        try {
            error_log("Routing side: $page");
    
            // Opret PageController
            $pageController = new PageController();
    
            // Kontrollér om metode findes
            if (!method_exists($pageController, 'showPage')) {
                throw new Exception("showPage metoden findes ikke i PageController.");
            }
    
            // Kør sidehåndtering
            $pageController->showPage($page);
        } catch (Exception $e) {
            error_log("Router fejl: " . $e->getMessage());
    
            // Fallback til fejlside
            $errorController = new ErrorController();
            $errorController->show404("Page not found: $page");
        }
    }
}
