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
