<?php 
class ErrorController {
  private static $errorHandled = false;

public function show404($message = 'The page you requested could not be found.') {
    if (self::$errorHandled) return;
    self::$errorHandled = true;

    error_log("404 Error: $message");
    http_response_code(404);
    $this->renderErrorPage(404, $message);
}

    public function show500($message = 'Something went wrong. Please try again later.') {
        error_log("500 Error: $message");
        http_response_code(500);
        $this->renderErrorPage(500, $message);
    }


    private function renderErrorPage($errorCode, $errorMessage) {
        $additionalData = [
            'error_code' => $errorCode,
            'message' => $errorMessage,
            'timestamp' => date('Y-m-d H:i:s'),
        ];

        $errorViewPath = __DIR__ . "/../../view/Error/{$errorCode}.php";

        if (file_exists($errorViewPath)) {
            include $errorViewPath;
        } else {
            echo "<h1>Error $errorCode</h1>";
            echo "<p>$errorMessage</p>";
        }

        exit; 
    }

    public function showErrorPage($message) {
        http_response_code(500); 
        $this->renderErrorPage(500, $message);
    }
}
