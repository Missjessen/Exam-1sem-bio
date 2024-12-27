<?php
require_once dirname(__DIR__, 2) . '/init.php';

class PageLoader {
    private $config;
    private $db;

    public function __construct($db) {
        $this->db = $db; 
        $this->config = require __DIR__ . '/../../config/loadPages.php';
        if (!is_array($this->config)) {
            throw new Exception("Konfigurationsfilen returnerede ikke et array.");
        }
    }

    public function loadAdminPage($viewName, $data = []) {
        $this->renderPage($viewName, $data, 'admin');
    }

    public function loadUserPage($viewName, $data = []) {
        $this->renderPage($viewName, $data, 'user');
    }

    /**
     * Generisk metode til at håndtere både user og admin views.
     */
    public function renderPage($viewName, $data = [], $type = 'user') {
        $current_page = $viewName;

        // Sikrer, at $data altid er et array
        if (!is_array($data)) {
            $data = [];
        }

        // Tilføj $page til data
        $data['page'] = $viewName;

        // Gør data tilgængelige som variabler
        extract($data);

        // Inkluder dynamisk CSS
        $this->includeCSS($viewName);



        // Inkluder header
        $headerFile = $type === 'admin' ? 'header_admin.php' : 'header_user.php';
        $this->includeLayout($headerFile, compact('current_page'));

        // Indlæs view baseret på type
        $viewPath = __DIR__ . "/../../app/view/$type/$viewName.php";

        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            throw new Exception("View-filen $viewName for $type kunne ikke indlæses.");
        }

        // Inkluder footer
        $footerFile = 'footer.php';
        $this->includeLayout($footerFile, compact('current_page'));
    }

    private function includeCSS($page) {
        echo "<link rel='stylesheet' href='/assets/css/$page.css'>";
    }

    private function includeLayout($layout, $data = []) {
        extract($data); 
        $layoutPath = __DIR__ . "/../../app/layout/$layout";

        if (file_exists($layoutPath)) {
            require $layoutPath;
        } else {
            throw new Exception("Layout-fil $layout ikke fundet.");
        }
    }

    public function renderErrorPage($errorCode, $errorMessage) {
        error_log("RenderErrorPage kaldt med kode: $errorCode og besked: $errorMessage");
    
        $additionalData = [
            'error_code' => $errorCode,
            'message' => $errorMessage,
            'timestamp' => date('Y-m-d H:i:s'),
        ];
    
        $errorViewPath = __DIR__ . "/../../view/Error/{$errorCode}.php";
    
        if (file_exists($errorViewPath)) {
            include $errorViewPath;
        } else {
            // Hvis fejlsiden ikke findes, fallback til en generisk fejlmeddelelse
            echo "<h1>Error $errorCode</h1>";
            echo "<p>$errorMessage</p>";
        }
    
        exit; // Sørg for at afslutte scriptet efter en fejl
    }
}    