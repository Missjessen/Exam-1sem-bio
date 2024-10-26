
<?php
spl_autoload_register(function ($class_name) {
        $paths = [
            __DIR__ . '/../app/controllers/',    // Kontroller denne sti
            __DIR__ . '/../app/view/controllers/', // Tilføj denne sti hvis relevant
            __DIR__ . '/../app/models/',         // Kontroller denne sti
            __DIR__ . '/../core/',               // Kontroller denne sti
        ];

    foreach ($paths as $path) {
        $file = $path . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;
            echo "Klasse $class_name fundet i $file <br>";
            return;
        } else {
            echo "Leder efter: $file - IKKE FUNDET <br>";
        }
    }

    echo "Autoload fejlede for klasse: $class_name <br>";
});
?>
