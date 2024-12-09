<?php

class PageLoader {
    private $config;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->config = require __DIR__ . '/../../config/loadPages.php';

        if (!is_array($this->config)) {
            throw new Exception("Konfigurationsfilen skal returnere et array.");
        }
    }

    public function loadUserPage($page, $data = []) {
        $this->includeCSS($page);
        $this->includeLayout('header_user.php');
        $this->includeView($page, $data);
        $this->includeLayout('footer.php');
    }

    private function includeCSS($page) {
        $cssPath = "/Exam-1sem-bio/assets/css/$page.css";
        if (file_exists(__DIR__ . '/../../' . $cssPath)) {
            echo "<link rel='stylesheet' href='$cssPath'>";
        } else {
            echo "<!-- CSS-fil ikke fundet: $cssPath -->";
        }
    }

    private function includeLayout($layout, $data = []) {
        $layoutPath = __DIR__ . "/../../app/layout/$layout";

        if (!file_exists($layoutPath)) {
            throw new Exception("Layout-fil ikke fundet: $layoutPath");
        }

        extract($data);
        require $layoutPath;
    }

    private function includeView($page, $data = []) {
        $viewPath = $this->config['pages'][$page]['view'] ?? null;

        if ($viewPath) {
            $fullPath = __DIR__ . '/../..' . $viewPath;

            if (!file_exists($fullPath)) {
                throw new Exception("View-fil ikke fundet: $fullPath");
            }

            extract($data);
            require $fullPath;
        } else {
            throw new Exception("View-konfiguration mangler for $page");
        }
    }
}
