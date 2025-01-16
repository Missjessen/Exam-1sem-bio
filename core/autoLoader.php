<?php
// Autoloader
spl_autoload_register(function ($class_name) {
    // Definer base sti til roden af projektet
    $basePath = dirname(__DIR__) . '/'; 

   
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
        $basePath . 'security/',
        
    ];

    // Loop gennem stierne og prøv at finde filen
    foreach ($paths as $path) {
        $file = $path . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;

            
            error_log("Klasse $class_name blev fundet i $file");
            return;
        }
    }

  
    error_log("Autoloader kunne ikke finde klasse: $class_name");
    die("Fejl: Klassen $class_name blev ikke fundet.");
});

