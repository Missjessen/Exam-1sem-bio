<?php 
class ErrorController {
    public function show404($message = 'The page you requested could not be found.') {
        http_response_code(404);
        $this->renderErrorPage(404, $message);
    }

    public function show500($message = 'Something went wrong. Please try again later.') {
        http_response_code(500);
        $this->renderErrorPage(500, $message);
    }

    private function renderErrorPage($errorCode, $errorMessage) {
        $additionalData = [
            'error_code' => $errorCode,
            'message' => $errorMessage,
            'timestamp' => date('Y-m-d H:i:s'),
        ];

        $errorViewPath = __DIR__ . "/../view/errors/{$errorCode}.php";

        if (file_exists($errorViewPath)) {
            include $errorViewPath;
        } else {
            echo "<h1>Error $errorCode</h1>";
            echo "<p>$errorMessage</p>";
        }

        <?php

class ErrorController {
    public function showErrorPage($message) {
        // Indlæs en generisk fejlside med besked
        include 'views/error.php';
    }
}


        exit;
    }
}