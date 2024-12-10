<!-- <?php
require_once 'init.php';

class BaseController {
   
    protected $errorController;


    


    protected function handleError($errorMessage, $statusCode = 500) {
        if ($statusCode === 404) {
            $this->errorController->show404($errorMessage);
        } else {
            $this->errorController->show500($errorMessage);
        }
    }
    
}



 -->