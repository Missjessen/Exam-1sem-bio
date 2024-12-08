<?php

function loadEnv($filePath) {
    if (!file_exists($filePath)) {
        throw new Exception(".env-filen findes ikke: $filePath");
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        if (strpos($line, '=') === false) {
            throw new Exception("Ugyldig linje i .env-filen: $line");
        }
        list($key, $value) = explode('=', $line, 2);
        putenv(trim($key) . '=' . trim($value));
    }
}

try {
    loadEnv(__DIR__ . '/../.env');
} catch (Exception $e) {
    die("Fejl ved indlæsning af .env: " . $e->getMessage());
}
?>
