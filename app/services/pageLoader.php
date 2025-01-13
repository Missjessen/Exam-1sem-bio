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
        if (!is_array($data)) {
            $data = [];
        }
    
        $data['page'] = $viewName;
        extract($data);
    
        // Bestem header baseret på typen
        $headerFile = $type === 'admin' ? 'header_admin.php' : 'header_user.php';
        $this->includeLayout($headerFile, compact('viewName'));
    
        // Håndter specifikke cases for `admin_login`
        $viewPath = $type === 'admin' && $viewName === 'admin_login' 
            ? __DIR__ . "/../../app/view/admin/admin_login.php" 
            : __DIR__ . "/../../app/view/$type/$viewName.php";
    
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            throw new Exception("View-filen $viewName for $type kunne ikke indlæses.");
        }
    
        // Footer inkluderes altid
        $footerFile = 'footer.php';
        $this->includeLayout($footerFile, compact('viewName'));
    }
    



    private function includeCSS($page) {
        echo "<link rel='stylesheet' href='/assets/css/$page.css'>";
    }

    private function includeLayout($layout, $data = []) {
    try {
        extract($data); 
        $layoutPath = __DIR__ . "/../../app/layout/$layout";
        if (file_exists($layoutPath)) {
            require $layoutPath;
        } else {
            throw new Exception("Layout-fil $layout ikke fundet.");
        }
    } catch (Exception $e) {
        error_log("Layout fejl: " . $e->getMessage());
        $this->renderErrorPage(500, "Fejl ved indlæsning af layout: $layout");
    }
}


  public function renderErrorPage($errorCode, $errorMessage) {
    try {
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
            throw new Exception("Error view ikke fundet for kode: $errorCode");
        }
    } catch (Exception $e) {
        error_log("RenderErrorPage fejl: " . $e->getMessage());
        echo "<h1>Error $errorCode</h1>";
        echo "<p>$errorMessage</p>";
    }

    exit;
}

}
