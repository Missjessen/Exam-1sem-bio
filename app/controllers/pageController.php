<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/init.php'; // Inkluder init.php med $db og autoloader

// PageController.php - Klasses ansvar for at kontrollere bruger- og admin-sider
class PageController {
    private $pageLoader;

    public function __construct($db) {
        $this->pageLoader = new PageLoader($db);
    }

    // Indlæs en side (bruges til både bruger og admin)
    public function loadPage($page) {
        $this->pageLoader->loadPage($page);
    }
}
