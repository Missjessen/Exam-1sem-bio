<?php
require_once 'init.php'; // require_once 'init.php'; // 
class PageLoader {
    private $config;
    private $db;

     public function __construct($db) {
        $this->db = $db; // Fjern singletonkald fra PageLoader
        $this->config = require __DIR__ . '/../../config/loadPages.php'; // Opdateret sti
        if (!is_array($this->config)) {
            throw new Exception("Konfigurationsfilen returnerede ikke et array.");
        }
    }

    

    public function loadUserPage($page) {
        $this->includeCSS($page);
        $this->includeLayout('header_user.php');
        $this->includeView($page);
        $this->includeLayout('footer.php');
    }

    public function loadAdminPage($viewName, $data = []) {
        $current_page = $viewName; // Markér den aktuelle side
        // Sikrer at $data altid er et array
    if (!is_array($data)) {
        $data = [];
    }

     // Tilføj $page til data
     $data['page'] = $viewName;

    extract($data); 
    
        // Inkludér CSS
        $this->includeCSS($viewName);
    
        // Inkludér header, view og footer
        $this->includeLayout('header_admin.php', compact('current_page'));
        $viewPath = __DIR__ . "/../../app/view/admin/$viewName.php";
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            error_log("View-fil $viewPath ikke fundet.");
            echo "Fejl: View kunne ikke indlæses.";
        }
        $this->includeLayout('footer.php', compact('current_page'));
    }
    
    private function includeCSS($page) {
        // Håndter CSS-indlæsning
        echo "<link rel='stylesheet' href='/Exam-1sem-bio/assets/css/$page.css'>";
    }
    
    private function includeLayout($layout, $data = []) {
        extract($data); // Gør data tilgængelige som variabler
        $layoutPath = __DIR__ . "/../../app/layout/$layout";
        if (file_exists($layoutPath)) {
            require $layoutPath;
        } else {
            ErrorController("Layout-fil $layoutPath ikke fundet.");
            echo "Fejl: Layout kunne ikke indlæses.";
        }
    }

    private function includeView($page) {
        $viewPath = $this->config['pages'][$page]['view'] ?? null;
    
        if ($viewPath) {
            $fullPath = __DIR__ . '/../..' . $viewPath;
    
            if (file_exists($fullPath)) {
                include $fullPath;
            } else {
                // Hvis view-filen ikke findes, vis 404
                $this->renderErrorPage(404, "View not found for page: $page");
            }
        } else {
            // Hvis view-konfigurationen mangler, vis 404
            $this->renderErrorPage(404, "No view configuration found for page: $page");
        }
    }
    
    private function renderErrorPage($errorCode, $errorMessage) {
        // Gør fejldata tilgængelige for view
        $additionalData = [
            'error_code' => $errorCode,
            'message' => $errorMessage,
            'timestamp' => date('Y-m-d H:i:s'),
        ];
    
        // Find fejlsiden baseret på fejlkoden (404 eller 500)
        $errorViewPath = __DIR__ . "/../view/errors/{$errorCode}.php";
    
        if (file_exists($errorViewPath)) {
            include $errorViewPath;
        } else {
            // Simpel fallback, hvis fejlsiden mangler
            echo "<h1>Error $errorCode</h1>";
            echo "<p>$errorMessage</p>";
        }
    
        exit; // Stop yderligere eksekvering
    }
    

   

    

    

   

    
}