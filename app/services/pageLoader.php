<?php

// /app/services/PageLoader.php
class PageLoader {
    private $config;

    public function __construct() {
        // Indlæs konfigurationsfilen for at få tilladte sider
        $this->config = require $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/config/loadPages.php';
    }

    // Funktion til at indlæse CSS for en side
    public function loadCss($page) {
        // Whitelist validering
        $allowedPages = array_keys($this->config['pages']);
        if (!in_array($page, $allowedPages)) {
            $page = 'homePage'; // Standard side hvis ukendt side anmodes
        }

        // Sanitere CSS sti
        if (isset($this->config['pages'][$page]['css'])) {
            $cssPath = htmlspecialchars($this->config['pages'][$page]['css'], ENT_QUOTES, 'UTF-8');
            echo '<link rel="stylesheet" href="' . $cssPath . '">';
        } else {
            $defaultCss = htmlspecialchars($this->config['default_css'], ENT_QUOTES, 'UTF-8');
            echo '<link rel="stylesheet" href="' . $defaultCss . '">';
        }
    }

    // Funktion til at indlæse side-indholdet
    public function loadPage($page) {
        // Whitelist validering
        $allowedPages = array_keys($this->config['pages']);
        if (!in_array($page, $allowedPages)) {
            $page = 'homePage'; // Standard side hvis ukendt side anmodes
        }

        // Sanitere side-fil sti for at undgå directory traversal
        $filePath = $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/' . BASENAME($this->config['pages'][$page]['file']);

        if (file_exists($filePath)) {
            include $filePath;
        } else {
            // Log fejl, hvis filen ikke kan findes, og vis en sikker fejlmeddelelse
            error_log("Fejl: Filen kunne ikke findes på stien: $filePath", 3, $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/logs/errors.log');
            echo "<p>Der opstod en fejl. Kontakt administratoren.</p>";
        }
    }
}
?>