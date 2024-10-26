<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/logs/errors.log');



require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/config/connection.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/core/autoloader.php';


class PageController {
    private $userPages = [
        'home' => ['view' => 'user/homePage.php', 'assets' => '/css/homePage.css'],
        'program' => ['view' => 'user/program.php', 'assets' => '/css/program.css'],
        'movie' => ['view' => 'user/movie.php', 'assets' => '/assets/css/movie.css'],
        'about' => ['view' => 'user/about.php', 'assets' => '/assets/css/about.css'],
    ];

    private $adminPages = [
        'dashboard' => ['view' => 'admin/dashboard.php', 'assets' => '/css/admin_styles.css'],
        'manage_pages' => ['view' => 'admin/manage_pages.php', 'assets' => '/css/admin_styles.css'],
        'settings' => ['view' => 'admin/settings.php', 'assets' => '/css/admin_styles.css'],
        'manage_user' => ['view' => 'admin/manage_user.php', 'assets' => '/css/admin_styles.css'],
        'admin_movie' => ['view' => 'admin/admin_movie.php', 'assets' => '/css/admin_movie.css'],
      
    ];

    // Indlæs en bruger-side
    public function loadUserPage($page) {
        $this->loadPage($page, $this->userPages, 'user');
        
    }
    

    // Indlæs en admin-side
    public function loadAdminPage($page) {
        $this->loadPage($page, $this->adminPages, 'admin');
    }

    // Funktion til at indlæse en side
    private function loadPage($page, $pages, $userType) {
        // Hvis siden ikke findes i listen, brug standarden
        if (!isset($pages[$page])) {
            $page = $userType === 'admin' ? 'dashboard' : 'home';
        }

        $pageData = $pages[$page];

        // Inkluder den korrekte header baseret på bruger-type
        $headerFile = $userType === 'admin' ? 'header_admin.php' : 'header_user.php';
        $headerPath = $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/app/layouts/' . $headerFile;

        

        if (file_exists($headerPath)) {
            include $headerPath;
        } else {
            echo "<p>Fejl: Header kunne ikke findes på stien: $headerPath</p>";
        }

        // Inkluder CSS-filen
        if (isset($pageData['assets'])) {
            echo '<link rel="stylesheet" href="/Exam-1sem-bio/assets' . $pageData['assets'] . '">';
        }

        // Inkluder View-filen
        $filePath = $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/app/views/' . $pageData['view'];
        if (file_exists($filePath)) {
            include $filePath;
        } else {
            echo "<p>Fejl: Filen kunne ikke findes på stien: $filePath</p>";
        }
       


        // Inkluder footer
        $footerPath = $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/app/layouts/footer.php';
        if (file_exists($footerPath)) {
            include $footerPath;
        } else {
            echo "<p>Fejl: Footer kunne ikke findes på stien: $footerPath</p>";
        }
    }
}
?>
