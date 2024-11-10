<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/init.php';

class PageLoader {
    private $config;
    private $db;

    public function __construct($db) {
        $this->config = require $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/config/loadPages.php';
        $this->db = $db;
    }

    public function loadCss($page) {
        $cssPath = $this->config['pages'][$page]['css'] ?? $this->config['default_css'];
        $cssPath = htmlspecialchars($cssPath, ENT_QUOTES, 'UTF-8');
        
        // Tilføj 'Exam-1sem-bio' præfix, hvis det mangler
        if (!str_starts_with($cssPath, '/Exam-1sem-bio')) {
            $cssPath = '/Exam-1sem-bio' . $cssPath;
        }
    
        // Debugging: Tjek om filen eksisterer
        $absolutePath = $_SERVER['DOCUMENT_ROOT'] . $cssPath;
        if (!file_exists($absolutePath)) {
            error_log("Fejl: CSS filen kunne ikke findes på stien: $absolutePath", 3, $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/logs/errors.log');
            echo "<!-- CSS filen kunne ikke findes: $cssPath -->";
        } else {
            echo "<!-- CSS Path: $cssPath -->"; // Debugging linje
            echo "<link rel='stylesheet' href='$cssPath'>";
        }
    }

    

    public function loadUserPage($page) {
        $this->loadPageWithLayout($page, 'header_user.php');
    }

    public function loadAdminPage($page, $data = []) {
        extract($data); // Gør det muligt at bruge $movies direkte i view
        $this->loadPageWithLayout($page, 'header_admin.php');
    }

    private function loadPageWithLayout($page, $headerFile) {
        $headerPath = $_SERVER['DOCUMENT_ROOT'] . "/Exam-1sem-bio/app/layout/$headerFile";
        $footerPath = $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/app/layout/footer.php';

        if (file_exists($headerPath)) {
            include $headerPath;
        }
        
        $viewPath = $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio' . ($this->config['pages'][$page]['view'] ?? '/app/view/user/homePage.php');
        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            $this->logAndDisplayError("Fejl: Filen kunne ikke findes på stien: $viewPath");
        }

        if (file_exists($footerPath)) {
            include $footerPath;
        } else {
            $this->logAndDisplayError("Fejl: Footer filen kunne ikke findes på stien: $footerPath");
        }
    }

    private function logAndDisplayError($message) {
        error_log($message . "\n", 3, $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/logs/errors.log');
        echo "<p>Der opstod en fejl. Kontakt administratoren.</p>";
    }
}
