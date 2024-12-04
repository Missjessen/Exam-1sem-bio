<?php
// Aktivér fejlrapportering for udvikling
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once BASE_PATH . '/init.php';

spl_autoload_register(function ($class_name) {
    $paths = [
        BASE_PATH . '/app/controllers/',
        BASE_PATH . '/app/models/',
        BASE_PATH . '/app/services/',
        BASE_PATH . '/app/view/admin/',
        BASE_PATH . '/app/view/user/',
        BASE_PATH . '/app/layout/',
        BASE_PATH . '/core/',
        BASE_PATH . '/config/',
        BASE_PATH . '/auth/',
    ];

    foreach ($paths as $path) {
        $file = $path . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;
            error_log("Klasse $class_name fundet i $file");
            return;
        }
    }
    error_log("Klasse $class_name ikke fundet.");
});

