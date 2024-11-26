<?php

function loadEnv($filePath) {
    if (!file_exists($filePath)) {
        throw new Exception(".env filen blev ikke fundet: $filePath");
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0 || trim($line) === '') {
            continue; // Ignorer kommentarer og tomme linjer
        }

        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}
