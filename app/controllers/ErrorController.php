<?php
class ErrorController {
    public function show404($message = 'The page you requested could not be found.') {
        http_response_code(404);
        $this->logError(404, $message);
        $this->renderErrorPage(404, $message);
    }

    public function show500($message = 'Something went wrong. Please try again later.') {
        http_response_code(500);
        $this->logError(500, $message);
        $this->renderErrorPage(500, $message);
    }

    public function showErrorPage($message) {
        http_response_code(500); 
        $this->logError(500, $message);
        $this->renderErrorPage(500, $message);
    }

    private function renderErrorPage($errorCode, $errorMessage) {
        $additionalData = [
            'error_code' => $errorCode,
            'message' => $errorMessage,
            'timestamp' => date('Y-m-d H:i:s'),
        ];

       
        $errorViewPath = __DIR__ . "/../../view/Error/{$errorCode}.php";
        header('Content-Type: text/html; charset=UTF-8');

        if (file_exists($errorViewPath)) {
            include $errorViewPath;
        } else {
            echo "<!DOCTYPE html>
                <html lang='en'>
                <head>
                    <meta charset='UTF-8'>
                    <title>Error $errorCode</title>
                </head>
                <body>
                    <h1>Error $errorCode</h1>
                    <p>$errorMessage</p>
                    <p><small>{$additionalData['timestamp']}</small></p>
                </body>
                </html>";
        }

        exit; 
    }

    private function logError($errorCode, $errorMessage) {
        $logMessage = "[" . date('Y-m-d H:i:s') . "] Error $errorCode: $errorMessage" . PHP_EOL;
        error_log($logMessage, 3, __DIR__ . '/../../logs/error.log');
    }
}