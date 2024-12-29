<?php
// test_loader.php
// Testfil til at debugge og finde den korrekte sti til loadPages.php

// Definer de stier, vi ønsker at teste
$testPaths = [
    __DIR__ . '/../../../config/loadPages.php', // Forventet korrekt sti
    __DIR__ . '/../../config/loadPages.php',
    __DIR__ . '/../config/loadPages.php',
    __DIR__ . '/config/loadPages.php'
];

// Loop gennem stierne og test dem
foreach ($testPaths as $path) {
    echo "Tester sti: $path\n";
    if (file_exists($path)) {
        echo "✔️ Filen findes: $path\n";
    } else {
        echo "❌ Filen mangler: $path\n";
    }
}

// Debug __DIR__ for at sikre, hvor vi er
echo "\n__DIR__: " . __DIR__ . "\n";

// Test den endelige sti
$finalPath = __DIR__ . '/../../../config/loadPages.php';
if (file_exists($finalPath)) {
    echo "\n✔️ Den korrekte sti til loadPages.php er: $finalPath\n";
    try {
        $config = require $finalPath;
        echo "✔️ loadPages.php blev korrekt indlæst.\n";
    } catch (Exception $e) {
        echo "❌ Der opstod en fejl ved indlæsning: " . $e->getMessage() . "\n";
    }
} else {
    echo "\n❌ Den forventede fil mangler: $finalPath\n";
}

?>
