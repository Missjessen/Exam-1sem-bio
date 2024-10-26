<?php
// /app/controllers/PageController.php
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
        'settings' => ['view' => 'admin/settings.php', 'assets' => 'css/admin_styles.css'],
        'manage_user' => ['view' => 'admin/manage_user.php', 'assets' => 'css/admin_styles.css'],
        'admin_movie' => ['view' => 'admin/admin_movie.php', 'assets' => '/css/admin_movie.css'],
    ];

    public function loadUserPage($page) {
        $this->loadPage($page, $this->userPages, 'user');
    }

    public function loadAdminPage($page) {
        $this->loadPage($page, $this->adminPages, 'admin');
    }

    private function loadPage($page, $pages, $userType) {
        if (!isset($pages[$page])) {
            $page = $userType === 'admin' ? 'dashboard' : 'home';
        }

        $pageData = $pages[$page];

        // Inkluder den korrekte header
        $headerFile = $userType === 'admin' ? 'header_admin.php' : 'header_user.php';
        include $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/app/layouts/' . $headerFile;

        // Inkluder CSS
        if (isset($pageData['css'])) {
            echo '<link rel="stylesheet" href="' . $pageData['css'] . '">';
        }

        // Inkluder View
        $filePath = $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/app/views/' . $pageData['view'];
        if (file_exists($filePath)) {
            include $filePath;
        } else {
            echo "<p>Fejl: Filen kunne ikke findes på stien: $filePath</p>";
        }

        // Inkluder footer
        include $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/app/layouts/footer.php';
    }
}
?>