<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/logs/errors.log');



require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/config/connection.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/core/autoloader.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/app/layout/header_admin.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/app/layout/footer.php';


// PageController.php - Klasses ansvar for at kontrollere bruger- og admin-sider
class PageController {
    private $pageLoader;

    public function __construct() {
        $this->pageLoader = new PageLoader();
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

