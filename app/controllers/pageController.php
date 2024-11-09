<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/init.php'; // Inkluder init.php med $db og autoloader

// PageController.php - Klasses ansvar for at kontrollere bruger- og admin-sider
class PageController {
    private $pageLoader;

    public function __construct($db) {
        $this->pageLoader = new PageLoader($db);
    }

    // Indlæs en bruger-side
    public function loadUserPage($page) {
        $this->loadHeader('user');
        $this->pageLoader->loadCss($page);
        $this->pageLoader->loadPage($page);
        $this->loadFooter();
    }

    // Indlæs en admin-side
    public function loadAdminPage($page) {
        $this->loadHeader('admin');
        $this->pageLoader->loadCss($page);
        $this->pageLoader->loadPage($page);
        $this->loadFooter();
    }

    // Indlæs header baseret på bruger-type
    private function loadHeader($userType) {
        $headerFile = $userType === 'admin' ? 'header_admin.php' : 'header_user.php';
        $headerPath = $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/app/layout/' . $headerFile;

        if (file_exists($headerPath)) {
            include $headerPath;
        } else {
            echo "<p>Fejl: Header kunne ikke findes på stien: $headerPath</p>";
        }
    }

    // Indlæs footer
    private function loadFooter() {
        $footerPath = $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/app/layout/footer.php';
        if (file_exists($footerPath)) {
            include $footerPath;
        } else {
            echo "<p>Fejl: Footer kunne ikke findes på stien: $footerPath</p>";
        }
    }
    
}
