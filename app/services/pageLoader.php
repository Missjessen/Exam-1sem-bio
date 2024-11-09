<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/init.php'; // Inkluder init.php med $db og autoloader

class PageLoader {
    private $config;
    private $db;

    public function __construct($db) {
        $this->config = require $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/config/loadPages.php';
        $this->db = $db;
    
        if (!$this->config || !isset($this->config['pages'])) {
            $this->logAndDisplayError("Fejl: Konfigurationsfilen kunne ikke indlæses korrekt.");
        } else {
            error_log("Konfigurationsfil indlæst korrekt.\n", 3, $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/logs/errors.log');
        }
    }
    

    public function loadCss($page) {
        $allowedPages = array_keys($this->config['pages']);
        if (!in_array($page, $allowedPages)) {
            $page = 'home';
        }
    
        $cssPath = $this->config['pages'][$page]['css'] ?? $this->config['default_css'];
        $cssPath = htmlspecialchars($cssPath, ENT_QUOTES, 'UTF-8');
    
        echo "<link rel='stylesheet' href='$cssPath'>";
    }

    public function loadPage($page, $params = []) {
        $allowedPages = array_keys($this->config['pages']);
        if (!in_array($page, $allowedPages) || !isset($this->config['pages'][$page]['view'])) {
            $page = 'home'; // Standard side hvis ukendt side anmodes
        }

        $this->loadHeader($page);
        $viewPath = $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio' . $this->config['pages'][$page]['view'];



        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            $this->logAndDisplayError("Fejl: Filen kunne ikke findes på stien: $viewPath");
        }

        $footerPath = $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/app/layout/footer.php';
        if (file_exists($footerPath)) {
            include $footerPath;
        } else {
            $this->logAndDisplayError("Fejl: Footer filen kunne ikke findes på stien: $footerPath");
        }
    }

    private function loadHeader($page) {
        $headerFile = (strpos($page, 'admin') === 0) ? 'header_admin.php' : 'header_user.php';
        $headerPath = $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/app/layout/' . $headerFile;

        if (file_exists($headerPath)) {
            include $headerPath;
        } else {
            $this->logAndDisplayError("Fejl: Header filen kunne ikke findes på stien: $headerPath");
        }
    }

    private function logAndDisplayError($message) {
        error_log($message . "\n", 3, $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/logs/errors.log');
        echo "<p>Der opstod en fejl. Kontakt administratoren.</p>";
    }
}


