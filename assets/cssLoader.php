<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/app/services/pageLoader.php';

class CssLoader {
    private $pageLoader;

    public function __construct() {
        $this->pageLoader = new PageLoader();
    }

    // Funktion til at indlæse CSS for en side
    public function loadCss($page) {
        $this->pageLoader->loadCss($page);
    }
}
?>