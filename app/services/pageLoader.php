<?php
require_once dirname(__DIR__, 2) . '/init.php';
class PageLoader {
    private $config;
    private $db;

     public function __construct($db) {
        $this->db = $db; // Fjern singletonkald fra PageLoader
        $this->config = require __DIR__ . '/../../config/loadPages.php'; // Opdateret sti
        if (!is_array($this->config)) {
            throw new Exception("Konfigurationsfilen returnerede ikke et array.");
            var_dump($this->config);
        }
       

    }

    public function loadUserPage($page, $data = []) {
        // Inkludér layout og view
        $this->includeCSS($page);
        $this->includeLayout('header_user.php'); // Ingen data nødvendig her
        $this->includeView($page, $data); // Send data direkte til view
        $this->includeLayout('footer.php'); // Ingen data nødvendig her
    }
    

    public function showHomePage(MovieFrontendModel $model) {
        try {
            // Hent data fra modellen
            $data = [
                'upcomingMovies' => $model->getUpcomingMovies() ?? [],
                'newsMovies' => $model->getNewsMovies() ?? [],
                'dailyMovies' => $model->getDailyShowings() ?? [],
                'genreMovies' => $model->getGenreMovies() ?? [],
                'settings' => $model->getSiteSettings() ?? [],
                'randomGenreMovies' => $model->getRandomGenreMovies(),
                'allGenres' => $model->getAllGenres(),
                'selectedGenre' => $_GET['genre'] ?? null,
            ];
    
            // Hvis der er valgt en genre, hent film for den genre
            if (!empty($data['selectedGenre'])) {
                $data['moviesByGenre'] = $model->getMoviesByGenre($data['selectedGenre']);
            }
    
            // Indlæs layout og view
            $this->includeCSS('homePage');
            $this->includeLayout('header_user.php', $data);
            $this->includeView('homePage', $data);
            $this->includeLayout('footer.php', $data);

            
    
        } catch (Exception $e) {
            // Log fejlen og vis en fejlbesked
    /*         error_log("Fejl i showHomePage: " . $e->getMessage());
            $this->renderErrorPage(500, "Noget gik galt under indlæsningen af startsiden."); */
        }
    }
    
    public function loadAdminPage($viewName, $data = []) {
        $current_page = $viewName; // Markér den aktuelle side
    
        // Sikrer at $data altid er et array
        if (!is_array($data)) {
            $data = [];
        }
    
        // Tilføj $page til data
        $data['page'] = $viewName;
    
        // Gør data tilgængelig som variabler
        extract($data);
    
        // Inkludér CSS
        $this->includeCSS($viewName);
    
        // Inkludér header
        $this->includeLayout('header_admin.php', compact('current_page'));
    
        // Dynamisk håndtering af visninger
        $viewPath = __DIR__ . "/../../app/view/admin/$viewName.php";
    
        if (file_exists($viewPath)) {
            // Hvis viewet er admin_daily_showings, tilpas yderligere
            if ($viewName === 'admin_daily_showings') {
               
            }
    
            // Inkludér view
            require $viewPath;
        } else {
            // Fejl: View-fil findes ikke
            echo "Fejl: View kunne ikke indlæses for $viewName.";
        }
    
        // Inkludér footer
        $this->includeLayout('footer.php', compact('current_page'));
    }
    
    private function includeCSS($page) {
        // Håndter CSS-indlæsning
        echo "<link rel='stylesheet' href='/Exam-1sem-bio/assets/css/$page.css'>";
    }
    
    private function includeLayout($layout, $data = []) {
        extract($data); // Gør data tilgængelige som variabler
        $layoutPath = __DIR__ . "/../../app/layout/$layout";
        if (file_exists($layoutPath)) {
            require $layoutPath;
        } else {
            ErrorController("Layout-fil $layoutPath ikke fundet.");
            echo "Fejl: Layout kunne ikke indlæses.";
        }
    }
    private function includeView($page, $data = []) {
        $viewPath = $this->config['pages'][$page]['view'] ?? null;
    
        if ($viewPath) {
            $fullPath = __DIR__ . '/../..' . $viewPath;
    
            if (file_exists($fullPath)) {
                if (!empty($data)) {
                    extract($data); // Gør data tilgængelige som variabler i view
                }
                include $fullPath;
            } else {
                // Hvis view-filen ikke findes, vis 404
                $this->renderErrorPage(404, "View not found for page: $page");
            }
        } else {
            // Hvis view-konfigurationen mangler, vis 404
            $this->renderErrorPage(404, "No view configuration found for page: $page");
        }
    }
    
    private function renderErrorPage($errorCode, $errorMessage) {
        // Gør fejldata tilgængelige for view
        $additionalData = [
            'error_code' => $errorCode,
            'message' => $errorMessage,
            'timestamp' => date('Y-m-d H:i:s'),
        ];
    
        // Find fejlsiden baseret på fejlkoden (404 eller 500)
        $errorViewPath = __DIR__ . "/../view/errors/{$errorCode}.php";
    
        if (file_exists($errorViewPath)) {
            include $errorViewPath;
        } else {
            // Simpel fallback, hvis fejlsiden mangler
            echo "<h1>Error $errorCode</h1>";
            echo "<p>$errorMessage</p>";
        }
        echo $viewPath; // For views
        echo $layoutPath; // For layouts
        echo "<!-- Forsøger at indlæse CSS: /Exam-1sem-bio/assets/css/$page.css -->";

        
        exit; // Stop yderligere eksekvering
    }
    

   

    

    

   

    
}