<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/init.php';

class PageLoader {
    private $config;
    private $db;

    public function __construct($db) {
        $this->config = require $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/config/loadPages.php';
        if ($db instanceof PDO) {
            error_log("PageLoader: Gyldig databaseforbindelse modtaget.");
        } else {
            error_log("PageLoader: Ugyldig databaseforbindelse.");
        }
        $this->db = $db;
    }

    public function loadUserPage($page) {
        $this->includeCSS($page);
        $this->includeLayout('header_user.php');
        $this->includeView($page);
        $this->includeLayout('footer.php');
    }

    public function loadAdminPage($page, $data = []) {
        extract($data); // Gør $movies, $actors, $genres tilgængelige i viewet
        $this->includeCSS($page);
        $this->includeLayout('header_admin.php');
        $this->includeView($page);
        $this->includeLayout('footer.php');
    }

    private function includeCSS($page) {
        $cssPath = $this->config['pages'][$page]['css'] ?? $this->config['default_css'];

        // Sørg for, at CSS-stien er korrekt formatteret
        if (!str_starts_with($cssPath, '/Exam-1sem-bio')) {
            $cssPath = '/Exam-1sem-bio' . $cssPath;
        }

        echo "<link rel='stylesheet' href='" . htmlspecialchars($cssPath, ENT_QUOTES, 'UTF-8') . "'>";
    }

    private function includeLayout($layout) {
        $path = $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/app/layout/' . $layout;
        if (file_exists($path)) {
            include $path;
        } else {
            error_log("Layout $layout ikke fundet.");
        }
    }

    private function includeView($page) {
        $viewPath = $this->config['pages'][$page]['view'] ?? null;

        if ($viewPath) {
            $fullPath = $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio' . $viewPath;
            if (file_exists($fullPath)) {
                $db = $this->db;
                include $fullPath;
            } else {
                error_log("View $viewPath ikke fundet.");
                echo "<p>Fejl: Viewet kunne ikke findes.</p>";
            }
        } else {
            error_log("Ingen view konfiguration fundet for $page.");
            echo "<p>Fejl: Ingen view konfiguration fundet.</p>";
           
        }
    }
}
