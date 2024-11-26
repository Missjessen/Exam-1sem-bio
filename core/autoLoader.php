
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/init.php';



spl_autoload_register(function ($class_name) {
    // Definer base sti til roden af projektet
    $basePathAbsolute = $_SERVER['DOCUMENT_ROOT'] . '/Exam-1sem-bio/';
    /* $basePathRelative = __DIR__ . '/../'; */

    echo "Base path: $basePathAbsolute<br>";



    $paths = [
        $basePathAbsolute . 'app/controllers/',
        $basePathAbsolute . 'app/models/',
        $basePathAbsolute . 'app/services/',
        $basePathAbsolute . 'core/',
        $basePathAbsolute . 'core/baseModel.php',
        $basePathAbsolute . 'app/layout/',
        $basePathAbsolute . 'app/view/admin/',
        $basePathAbsolute . 'app/view/user/',
        $basePathAbsolute . 'config/',
/* 
        $basePathRelative . 'app/controllers/',
        $basePathRelative . 'app/models/',
        $basePathRelative . 'app/services/',
        $basePathRelative . 'core/',
        $basePathRelative . 'core/baseModel/',
        $basePathRelative . 'app/layout/',
        $basePathRelative . 'app/view/',
        $basePathRelative . 'config/',  */
    ];

    foreach ($paths as $path) {
        $file = $path . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;
            // Debugging - du kan kommentere denne linje ud, når alt fungerer som forventet
            echo "Klasse $class_name fundet i $file <br>";
            return;
           
        }
    }

    // Debugging: Kommentér denne del ud, når alt fungerer
    echo "Klasse $class_name ikke fundet. Leder i følgende stier: <br>";
    foreach ($paths as $path) {
        echo "Leder i: $path <br>";
    }
  
});
