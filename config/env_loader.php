<?php

function loadEnv($filePath) {
    if (!file_exists($filePath)) {
        throw new Exception(".env-filen findes ikke: $filePath");
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $env = [];
    
    foreach ($lines as $line) {
        // Ignorer kommentarer
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        // Kontrollér formatet KEY=VALUE
        if (!str_contains($line, '=')) {
            throw new Exception("Ugyldig linje i .env-filen: $line");
        }

        list($key, $value) = explode('=', $line, 2);
        $env[trim($key)] = trim($value);
    }

    return $env;
}

try {
    $env = loadEnv(__DIR__ . '/.env');
    foreach ($env as $key => $value) {
        putenv("$key=$value");
    }
} catch (Exception $e) {
    die("Fejl ved indlæsning af .env: " . $e->getMessage());
}
