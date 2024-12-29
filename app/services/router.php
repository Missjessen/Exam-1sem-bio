<?php 
// Router
class Router {
    public static function route($page) {
        try {
            error_log("Routing side: $page");

            $db = Database::getInstance()->getConnection();
            $pageController = new PageController($db);

            if (!method_exists($pageController, 'showPage')) {
                throw new Exception("showPage metoden findes ikke i PageController.");
            }

            $pageController->showPage($page);
        } catch (Exception $e) {
            error_log("Router fejl: " . $e->getMessage());

            $errorController = new ErrorController();
            $errorController->show404("Page not found: $page");
        }
    }
}