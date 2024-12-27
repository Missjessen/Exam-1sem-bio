<?php

require_once dirname(__DIR__, 2) . '/init.php';

class Router {
    public static function route($page) {
        try {
            error_log("Routing side: $page");
    
            // Tjek om PageController kan instantieres
            $pageController = new PageController();
            error_log("PageController blev instantieret.");
    
            // Kontrollér om metode findes
            if (!method_exists($pageController, 'showPage')) {
                throw new Exception("Metoden showPage findes ikke i PageController.");
            }
    
            // Kør sidehåndtering
            $pageController->showPage($page);
        } catch (Exception $e) {
            error_log("Router fejl: " . $e->getMessage());
    
            // Fallback til fejlside
            try {
                $errorController = new ErrorController();
                $errorController->show404("Siden blev ikke fundet: $page");
            } catch (Exception $innerException) {
                error_log("Fejl i ErrorController: " . $innerException->getMessage());
                // Fallback til en simpel fejlmeddelelse
                echo "<h1>404 - Page Not Found</h1>";
                echo "<p>" . htmlspecialchars($innerException->getMessage()) . "</p>";
            }
        }
    }
}
