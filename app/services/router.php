<?php 
// Router
class Router {
    /**
     * Håndterer routing til den korrekte side.
     */
    public static function route($page) {
        try {
            error_log("Routing side: $page");

            $db = Database::getInstance()->getConnection();
            $pageController = new PageController($db);

            // Tjek for slug eller andre parametre
            $slug = $_GET['slug'] ?? null;

            // Kald PageController's showPage-metode
            if (method_exists($pageController, 'showPage')) {
                $pageController->showPage($page, $slug);
            } else {
                throw new Exception("showPage metoden findes ikke i PageController.");
            }
        } catch (Exception $e) {
            error_log("Router fejl: " . $e->getMessage());

            // Håndter 404-sider
            $errorController = new ErrorController();
            $errorController->show404("Page not found: $page");
        }
    }

    /**
     * Naviger til en bestemt side med valgfri parametre.
     */
    public static function navigateTo($page, $params = []) {
        $url = "index.php?page=$page";
        if (!empty($params)) {
            $queryString = http_build_query($params);
            $url .= "&$queryString";
        }
        header("Location: $url");
        exit;
    }

    /**
     * Fjerner uønskede parametre fra $_GET.
     */
    public static function cleanParams($paramsToRemove = ['slug']) {
        $cleanedParams = $_GET;
        foreach ($paramsToRemove as $param) {
            unset($cleanedParams[$param]);
        }
        return $cleanedParams;
    }
}
