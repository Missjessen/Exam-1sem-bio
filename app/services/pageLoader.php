<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/init.php'; // Inkluder init.php med $db og autoloader


class PageLoader {
    private $config;
    private $db;

    public function __construct($db) {
        $this->config = require $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/config/loadPages.php';
        $this->db = $db;

        if (!$this->config || !isset($this->config['pages'])) {
            $this->logAndDisplayError("Fejl: Konfigurationsfilen kunne ikke indlæses korrekt.");
        }
    }

    // Funktion til at indlæse CSS for en side
    public function loadCss($page) {
        $allowedPages = array_keys($this->config['pages']);
        if (!in_array($page, $allowedPages)) {
            $page = 'home'; // Standard side hvis ukendt side anmodes
        }

        // Hent CSS-sti, og brug default CSS hvis ikke specificeret
        $cssPath = $this->config['pages'][$page]['css'] ?? $this->config['default_css'];
        $cssPath = htmlspecialchars($cssPath, ENT_QUOTES, 'UTF-8');

        // Debugging output: udskriv den endelige CSS-sti
        echo "<!-- CSS sti: /Exam-1sem-bio" . $cssPath . " -->";

        // Indsæt CSS-linket i HTML
        echo '<link rel="stylesheet" href="/Exam-1sem-bio' . $cssPath . '">';
    }

    // Funktion til at indlæse side-indholdet, med parametre som UUID og slug
    public function loadPage($page, $params = []) {
        $allowedPages = array_keys($this->config['pages']);
        if (!in_array($page, $allowedPages) || !isset($this->config['pages'][$page]['view'])) {
            $page = 'home'; // Standard side hvis ukendt side anmodes
        }

        // Indlæs header baseret på sidetype
        $this->loadHeader($page);

        // Inkluder selve siden
        $viewPath = $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/' . $this->config['pages'][$page]['view'];

        if ($page === 'movie' && isset($params['uuid'])) {
            // Hent filmdetaljer, hvis siden er "movie" og UUID er sat
            $uuid = $params['uuid'];
            $movieDetails = $this->getMovieDetails($uuid);
            if ($movieDetails) {
                extract($movieDetails); // Ekstraher filmdetaljerne som variabler
                if (file_exists($viewPath)) {
                    include $viewPath;
                } else {
                    $this->logAndDisplayError("Fejl: Filen kunne ikke findes på stien: $viewPath");
                }
            } else {
                $this->logAndDisplayError("Fejl: Filmen kunne ikke findes for UUID: $uuid");
            }
        } else {
            // For alle andre sider uden UUID
            if (file_exists($viewPath)) {
                include $viewPath;
            } else {
                $this->logAndDisplayError("Fejl: Filen kunne ikke findes på stien: $viewPath");
            }
        }

        // Inkluder footer
        $footerPath = $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/app/layout/footer.php';
        if (file_exists($footerPath)) {
            include $footerPath;
        } else {
            $this->logAndDisplayError("Fejl: Footer filen kunne ikke findes på stien: $footerPath");
        }
    }

    private function loadHeader($page) {
        // Vælg header baseret på sidetype
        $headerFile = (strpos($page, 'admin') === 0) ? 'header_admin.php' : 'header_user.php';
        $headerPath = $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/app/layout/' . $headerFile;
    
        if (file_exists($headerPath)) {
            include $headerPath;
        } else {
            $this->logAndDisplayError("Fejl: Header filen kunne ikke findes på stien: $headerPath");
        }
    }

    private function getMovieDetails($uuid) {
        // Hent filmdetaljer fra databasen baseret på UUID ved hjælp af PDO
        try {
            $stmt = $this->db->prepare("SELECT * FROM movies WHERE uuid = :uuid");
            $stmt->bindParam(':uuid', $uuid, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logAndDisplayError("Fejl: Databaseforespørgsel mislykkedes: " . $e->getMessage());
            return false;
        }
    }

    private function logAndDisplayError($message) {
        error_log($message . "\n", 3, $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/logs/errors.log');
        echo "<p>Der opstod en fejl. Kontakt administratoren.</p>";
    }
}
?>
