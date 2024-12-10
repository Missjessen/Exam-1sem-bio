<?php
require_once dirname(__DIR__, 2) . '/init.php';
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


    
    public function loadAdminPage($viewName, $data = []) {
    $this->loadPage($viewName, $data, 'admin', 'header_admin.php', 'footer.php');
}

public function loadUserPage($viewName, $data = []) {
    $this->loadPage($viewName, $data, 'user', 'header_user.php', 'footer.php');
}

private function loadPage($viewName, $data, $type, $headerFile, $footerFile) {
    $current_page = $viewName; // Marker den aktuelle side

    // Sikrer, at $data altid er et array
    if (!is_array($data)) {
        $data = [];
    }

    // Tilføj $page til data
    $data['page'] = $viewName;

    // Gør data tilgængelige som variabler
    extract($data);

    // Dynamisk CSS
    $this->includeCSS($viewName);

    // Inkluder header
    $this->includeLayout($headerFile, compact('current_page'));

    // Dynamisk visning baseret på $type
    $viewPath = __DIR__ . "/../../app/view/$type/$viewName.php";

    if (file_exists($viewPath)) {
        require $viewPath;
    } else {
        throw new Exception("View-filen $viewName for $type kunne ikke indlæses.");
    }

    // Inkluder footer
    $this->includeLayout($footerFile, compact('current_page'));
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
    private function includeView($page, $data = []) {
        $viewPath = $this->config['pages'][$page]['view'] ?? null;
    
        if ($viewPath) {
            $fullPath = __DIR__ . '/../..' . $viewPath;
    
            if (file_exists($fullPath)) {
                if (!empty($data)) {
                    extract($data); // Gør data tilgængelige som variabler i view
                }
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
        echo $viewPath; // For views
        echo $layoutPath; // For layouts
        echo "<!-- Forsøger at indlæse CSS: /Exam-1sem-bio/assets/css/$page.css -->";

        
        exit; // Stop yderligere eksekvering
    }
    

   

    

    

   

    
}