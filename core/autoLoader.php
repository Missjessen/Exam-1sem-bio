<?php
// Aktivér fejlrapportering for udvikling
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Autoloader
spl_autoload_register(function ($class_name) {
    // Definer base sti til roden af projektet
    $basePath = dirname(__DIR__) . '/'; 

    // Definer de mapper, hvor autoloaderen skal lede efter filer
    $paths = [
        $basePath . 'app/controllers/',
        $basePath . 'app/models/',
        $basePath . 'app/services/',
        $basePath . 'core/',
        $basePath . 'app/layout/',
        $basePath . 'config/',
        $basePath . 'auth/',
        $basePath . 'auth/controller/',
        $basePath . 'auth/model/',
        
    ];

    // Loop gennem stierne og prøv at finde filen
    foreach ($paths as $path) {
        $file = $path . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;

            // Debugging: Log, hvor klassen blev fundet
            error_log("Klasse $class_name blev fundet i $file");
            return;
        }
    }

    // Hvis filen ikke blev fundet, log fejlen
    error_log("Autoloader kunne ikke finde klasse: $class_name");
    die("Fejl: Klassen $class_name blev ikke fundet.");
});

