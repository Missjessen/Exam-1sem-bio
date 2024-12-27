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
            error_log("Data er ikke et array, konverterer til tomt array.");
            $data = [];
        }

        // Gør data tilgængelige som variabler i views
        $data['page'] = $viewName;
        extract($data);

        // Inkluder dynamisk CSS
        $this->includeCSS($viewName);

        // Inkluder header
        $headerFile = $type === 'admin' ? 'header_admin.php' : 'header_user.php';
        $this->includeLayout($headerFile, compact('current_page'));

        // Indlæs det specificerede view
        $viewPath = __DIR__ . "/../../app/view/$type/$viewName.php";
        if (file_exists($viewPath)) {
            error_log("Indlæser view-fil: $viewPath");
            require $viewPath;
        } else {
            throw new Exception("View-filen $viewName for $type kunne ikke indlæses. Sti: $viewPath");
        }

        // Inkluder footer
        $footerFile = 'footer.php';
        $this->includeLayout($footerFile, compact('current_page'));
    }

    private function includeCSS($page) {
        $cssPath = "/assets/css/$page.css";
        error_log("Indlæser CSS: $cssPath");
        echo "<link rel='stylesheet' href='$cssPath'>";
    }

    private function includeLayout($layout, $data = []) {
        extract($data);
        $layoutPath = __DIR__ . "/../../app/layout/$layout";

        if (file_exists($layoutPath)) {
            error_log("Indlæser layout-fil: $layoutPath");
            require $layoutPath;
        } else {
            throw new Exception("Layout-fil $layout ikke fundet. Sti: $layoutPath");
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
            error_log("Indlæser fejl-view: $errorViewPath");
            include $errorViewPath;
        } else {
            error_log("Fejl-view mangler: $errorViewPath");
            echo "<h1>Error $errorCode</h1>";
            echo "<p>$errorMessage</p>";
        }

        exit; // Sørg for at afslutte scriptet efter en fejl
    }
}
