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

    function currentPageURL($page, $additionalParams = []) {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? "https" : "http");
        $host = $_SERVER['HTTP_HOST'];
        $uri = $_SERVER['REQUEST_URI'];
    
        // Parsér eksisterende query-parametre
        $queryParams = [];
        parse_str(parse_url($uri, PHP_URL_QUERY), $queryParams);
    
        // Opdater eller tilføj page-parametret
        $queryParams['page'] = $page;
    
        // Tilføj evt. ekstra parametre som slug
        foreach ($additionalParams as $key => $value) {
            $queryParams[$key] = $value;
        }
    
        // Generér ny URL med de opdaterede parametre
        $baseUri = strtok($uri, '?'); // Fjern eksisterende query-parametre fra URI
        $queryString = http_build_query($queryParams);
    
        return $protocol . '://' . $host . $baseUri . '?' . $queryString;
    }
}
