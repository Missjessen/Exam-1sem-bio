<?php

// Pageloader
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

    public function renderPage($viewName, $data = [], $type = 'user') {
        $current_page = $viewName;

        if (!is_array($data)) {
            $data = [];
        }

        $data['page'] = $viewName;
        extract($data);

        $this->includeCSS($viewName);

        $headerFile = $type === 'admin' ? 'header_admin.php' : 'header_user.php';
        $this->includeLayout($headerFile, compact('current_page'));

        $viewPath = __DIR__ . "/../../app/view/$type/$viewName.php";
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            throw new Exception("View-filen $viewName for $type kunne ikke indlæses.");
        }

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
            echo "<h1>Error $errorCode</h1>";
            echo "<p>$errorMessage</p>";
        }

        exit;
    }
}
