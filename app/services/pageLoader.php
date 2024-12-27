<?php

require_once dirname(__DIR__, 2) . '/init.php';

class PageLoader {
    private $config;
    private $db;

    public function __construct($db) {
        $this->db = $db;

        // Indlæs konfigurationsfilen
        $configPath = __DIR__ . '/../../config/loadPages.php';
        if (!file_exists($configPath)) {
            throw new Exception("Konfigurationsfilen $configPath kunne ikke findes.");
        }

        $this->config = require $configPath;
        if (!is_array($this->config)) {
            throw new Exception("Konfigurationsfilen $configPath returnerede ikke et array.");
        }
    }

    public function loadAdminPage($viewName, $data = []) {
        error_log("Indlæser admin-side: $viewName");
        $this->renderPage($viewName, $data, 'admin');
    }

    public function loadUserPage($viewName, $data = []) {
        error_log("Indlæser bruger-side: $viewName");
        $this->renderPage($viewName, $data, 'user');
    }

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
    
        // Tilpas stien baseret på viewets placering
        if ($type === 'auth') {
            $viewPath = __DIR__ . "/../../auth/view/$viewName.php";
        } else {
            $viewPath = __DIR__ . "/../../app/view/$type/$viewName.php";
        }
    
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
        $cssPath = "/assets/css/$page.css";
        if (file_exists(__DIR__ . "/../../" . $cssPath)) {
            echo "<link rel='stylesheet' href='" . BASE_URL . $cssPath . "'>";
        } else {
            error_log("CSS-fil $cssPath ikke fundet.");
        }
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
        http_response_code($errorCode);
        $errorViewPath = __DIR__ . "/../../app/view/errors/{$errorCode}.php";
        if (file_exists($errorViewPath)) {
            include $errorViewPath;
        } else {
            echo "<h1>Fejl $errorCode</h1>";
            echo "<p>$errorMessage</p>";
        }
        exit;
    }
}
