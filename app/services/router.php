<?php 
class Router {
    public static function route($page) {
        try {
            error_log("Routing side: $page");

            $db = Database::getInstance()->getConnection();
            $pageController = new PageController($db);

           
            $slug = ($page === 'movie_details' && isset($_GET['slug'])) ? $_GET['slug'] : null;

            if (!method_exists($pageController, 'showPage')) {
                throw new Exception("showPage metoden findes ikke i PageController.");
            }

        $pageController->showPage($page, $slug);
    } catch (Exception $e) {
        error_log("Router fejl: " . $e->getMessage());

        $errorController = new ErrorController();
        if (http_response_code() === 404) {
            exit; 
        }
        $errorController->show404("Page not found: $page");
    }
}
}
